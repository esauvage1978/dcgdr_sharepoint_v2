<?php

namespace App\Controller;

use App\Dto\UserDto;
use App\Entity\Rubric;
use App\Security\Role;
use App\Helper\Slugger;
use App\Dto\BackpackDto;
use App\Entity\Backpack;
use App\Tree\BackpackTree;
use App\Dto\UnderRubricDto;
use App\History\HistoryShow;
use App\Security\CurrentUser;
use App\Workflow\WorkflowData;
use App\Security\BackpackVoter;
use App\Helper\ParamsInServices;
use App\History\BackpackHistory;
use App\Manager\BackpackManager;
use App\Service\BackpackForTree;
use App\Service\BackpackMakerDto;
use App\Form\Backpack\BackpackType;
use App\Form\Backpack\BackpackNewType;
use App\Repository\BackpackRepository;
use App\Repository\BackpackDtoRepository;
use App\Repository\UnderRubricRepository;
use App\Repository\BackpackFileRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class ThematicController
 * @package App\Controller
 */
class BackpackController extends AbstractGController
{


    /**
     * @var BackpackMakerDto
     */
    private $backpackMakerDto;



    public function __construct
    (
        BackpackRepository $repository,
        BackpackDtoRepository $backpackDtoRepository,
        backpackManager $manager,
        UnderRubricRepository $underRubricRepository,
        CurrentUser $currentUser
    )
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'backpack';
        $this->underRubricRepository = $underRubricRepository;
        $this->backpackDtoRepository = $backpackDtoRepository;
        $this->backpackMakerDto = new BackpackMakerDto($currentUser->getUser());
    }

    /**
     * @Route("/backpack/add", name="backpack_add", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new Backpack(), BackpackNewType::class, false);
    }

    /**
     * @Route("/backpack/{id}/edit", name="backpack_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Backpack $item, backpackHistory $backpackHistory)
    {
        $this->denyAccessUnlessGranted(BackpackVoter::UPDATE, $item);
        $itemOld = clone($item);
        $form = $this->createForm(BackpackType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($item)) {
                $this->addFlash(self::SUCCESS, self::MSG_MODIFY);
                $backpackHistory->compare($itemOld, $item);
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($item));
            }

        }

        return $this->render('backpack/edit.html.twig', [
            'item' => $item,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @Route("/backpack/{id}", name="backpack_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Backpack $item)
    {
        $this->denyAccessUnlessGranted(BackpackVoter::READ, $item);

        return $this->render('backpack/show.html.twig', [
            'item' => $item
        ]);
    }


    /**
     * @Route("/backpack/{id}/history", name="backpack_history", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function history(Request $request, Backpack $item): Response
    {
        $this->denyAccessUnlessGranted(BackpackVoter::READ, $item);
        $historyShow = new HistoryShow(
            $this->generateUrl('backpack_edit', ['id' => $item->getId()]),
            "Porte-document : " . $item->getName(),
            "Historiques des modifications du porte-document"
        );

        return $this->render('backpack/history.html.twig', [
            'item' => $item,
            'histories' => $item->getHistories(),
            'data' => $historyShow->getParams()
        ]);
    }



    /**
     * @Route("/backpack/{id}", name="backpack_del", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, BackpackForTree $backpackForTree, Backpack $item)
    {
        $this->denyAccessUnlessGranted(BackpackVoter::DELETE, $item);

        $dto=new BackpackDto();
        $dto
            ->setStateCurrent($item->getStateCurrent())
            ->setUnderRubricDto((new UnderRubricDto())->setId($item->getUnderRubric()->getId()))
            ->setVisible(BackpackDto::TRUE);

        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $this->addFlash(self::SUCCESS, self::MSG_DELETE);
            $this->manager->remove($item);
        }

        $renderArray = $backpackForTree->getDatas($this->container, $request, null, $dto);
        return $this->render('backpack/tree.html.twig', $renderArray);


    }


    /**
     * @Route("/backpack/{id}/file/{fileId}", name="backpack_file_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actionFileShowAction(
        Request $request,
        Backpack $backpack,
        string $fileId,
        BackpackFileRepository $backpackFileRepository
    ): Response
    {
        //$this->denyAccessUnlessGranted(BackpackVoter::READ, $backpack);

        $actionFile = $backpackFileRepository->find($fileId);

        $file = new File($actionFile->getHref());

        return $this->file($file, Slugger::slugify($actionFile->getTitle()) . '.' . $actionFile->getFileExtension());
    }



}