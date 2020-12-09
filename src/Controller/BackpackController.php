<?php

namespace App\Controller;

use App\Dto\BackpackDto;
use App\Dto\UnderRubricDto;
use App\Dto\UserDto;
use App\Entity\Backpack;
use App\Entity\Rubric;
use App\Form\Backpack\BackpackNewType;
use App\Form\Backpack\BackpackType;
use App\Helper\ParamsInServices;
use App\Helper\Slugger;
use App\History\BackpackHistory;
use App\History\HistoryShow;
use App\Manager\BackpackManager;
use App\Repository\BackpackDtoRepository;
use App\Repository\BackpackFileRepository;
use App\Repository\BackpackRepository;
use App\Repository\UnderRubricRepository;
use App\Security\BackpackVoter;
use App\Security\CurrentUser;
use App\Security\Role;
use App\Service\BackpackMakerDto;
use App\Tree\BackpackTree;
use App\Workflow\WorkflowData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ThematicController
 * @package App\Controller
 */
class BackpackController extends AbstractGController
{
    /**
     * @var UnderRubricRepository
     */
    private $underRubricRepository;

    /**
     * @var BackpackDtoRepository
     */
    private $backpackDtoRepository;

    /**
     * @var BackpackMakerDto
     */
    private $backpackMakerDto;

    /**
     * @var ParamsInServices
     */
    private $paramsInServices;

    public function __construct
    (
        BackpackRepository $repository,
        BackpackDtoRepository $backpackDtoRepository,
        backpackManager $manager,
        UnderRubricRepository $underRubricRepository,
        ParamsInServices $paramsInServices,
        CurrentUser $currentUser
    )
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'backpack';
        $this->underRubricRepository = $underRubricRepository;
        $this->paramsInServices = $paramsInServices;
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
     * @Route("/backpacks/news/rubric/{id}", name="backpacks_news_for_rubric", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function newsForRubric(Request $request, string $id)
    {
        return $this->treeView($request,
            $this->backpackMakerDto->get(
                BackpackMakerDto::NEWS_FOR_RUBRIC, $id
            ));
    }

    /**
     * @Route("/backpacks/news/underrubric/{id}", name="backpacks_news_for_underrubric", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function newsForUnderRubric(Request $request, string $id)
    {
        return $this->treeView($request,
            $this->backpackMakerDto->get(
                BackpackMakerDto::NEWS_FOR_UNDERRUBRIC, $id
            ));
    }

    /**
     * @Route("/backpacks/news", name="backpacks_news", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function news(Request $request)
    {
        return $this->treeView($request, $this->backpackMakerDto->get(BackpackMakerDto::NEWS));
    }







    /**
     * @Route("/backpacks/hide", name="backpacks_hide", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showHide(Request $request)
    {
        return $this->treeView($request, $this->backpackMakerDto->get(BackpackMakerDto::HIDE));
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
    public function delete(Request $request, Backpack $item)
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

        return $this->treeView($request,$dto);
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




    /**
     * @Route("/backpacks/search", name="backpacks_search", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function search(Request $request): Response
    {
        $r = $request->get('r');
        if($r===null) {
            return $this->redirectToRoute('home');
        } else {
            return $this->treeView($request, $this->backpackMakerDto->get(BackpackMakerDto::SEARCH, $r));
        }
    }
}