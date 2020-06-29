<?php

namespace App\Controller;

use App\Dto\BackpackDto;
use App\Dto\UnderRubricDto;
use App\Dto\UserDto;
use App\Entity\Backpack;
use App\Entity\Rubric;
use App\Entity\UnderRubric;
use App\Form\Backpack\BackpackNewType;
use App\Form\Backpack\BackpackType;
use App\History\BackpackHistory;
use App\History\HistoryShow;
use App\Manager\BackpackManager;
use App\Repository\BackpackDtoRepository;
use App\Repository\BackpackFileRepository;
use App\Repository\BackpackRepository;
use App\Repository\UnderRubricRepository;
use App\Tree\BackpackTree;
use App\Workflow\WorkflowData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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
     * @var ParameterBagInterface
     */
    private $bag;
    public function __construct
    (
        BackpackRepository $repository,
        backpackManager $manager,
        UnderRubricRepository $underRubricRepository,
        ParameterBagInterface $params
    )
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'backpack';
        $this->underRubricRepository = $underRubricRepository;
        $this->bag=$params;
    }

    /**
     * @Route("/backpack/add", name="backpack_add", methods={"GET","POST"})
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new Backpack(), BackpackNewType::class, false);
    }

    /**
     * @Route("/backpack/edit/{id}", name="backpack_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Backpack $item, backpackHistory $backpackHistory)
    {

        //$this->denyAccessUnlessGranted(BackpackVoter::UPDATE, $backpack);
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
     * @Route("/backpacks/news", name="backpacks_news", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function news(
        Request $request,
        BackpackDtoRepository $repo
    )
    {
        $dto=new BackpackDto();
        $dto
            ->setCurrentState(WorkflowData::STATE_PUBLISHED)
            ->setIsNew(BackpackDto::TRUE)
            ->setVisible(BackpackDto::TRUE);

        return $this->search($request,$repo,$dto);
    }
    /**
     * @Route("/backpacks/abandonned", name="backpacks_abandonned", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_abandonned(
        Request $request,
        BackpackDtoRepository $repo
    )
    {
        $dto=new BackpackDto();
        $dto
            ->setCurrentState(WorkflowData::STATE_ABANDONNED)
            ->setVisible(BackpackDto::TRUE);

        return $this->search($request,$repo,$dto);
    }

    /**
     * @Route("/backpacks/archived", name="backpacks_archived", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_archived(
        Request $request,
        BackpackDtoRepository $repo
    )
    {
        $dto=new BackpackDto();
        $dto
            ->setCurrentState(WorkflowData::STATE_ARCHIVED)
            ->setVisible(BackpackDto::TRUE);

        return $this->search($request,$repo,$dto);
    }

    /**
     * @Route("/backpacks/draft", name="backpacks_draft", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_draft(
        Request $request,
        BackpackDtoRepository $repo
    )
    {
        $dto=new BackpackDto();
        $dto
            ->setCurrentState(WorkflowData::STATE_DRAFT)
            ->setVisible(BackpackDto::TRUE);

        return $this->search($request,$repo,$dto);
    }

    /**
     * @Route("/backpacks/published", name="backpacks_published", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_published(
        Request $request,
        BackpackDtoRepository $repo
    )
    {
        $dto=new BackpackDto();
        $dto
            ->setCurrentState(WorkflowData::STATE_PUBLISHED)
            ->setVisible(BackpackDto::TRUE);

        return $this->search($request,$repo,$dto);
    }

    /**
     * @Route("/backpacks/mydraft", name="backpacks_mydraft", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_mydraft(
        Request $request,
        BackpackDtoRepository $repo
    )
    {
        $dto=new BackpackDto();
        $dto
            ->setCurrentState(WorkflowData::STATE_DRAFT)
            ->setOwnerDto((New UserDto())->setId($this->getUser()->getId()))
            ->setVisible(BackpackDto::TRUE);

        return $this->search($request,$repo,$dto);
    }


    /**
     * @Route("/backpacks/hide", name="backpacks_hide", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showHide(
        Request $request,
        BackpackDtoRepository $repo
    )
    {
        $dto=new BackpackDto();
        $dto
            ->setHide(BackpackDto::TRUE);

        return $this->search($request,$repo,$dto);
    }

    /**
     * @Route("/backpack/{id}/history", name="backpack_history", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function history(
        Request $request,
        Backpack $item
    ): Response
    {
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
     * @Route("/underrubric/{id}", name="underrubric_show", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function showUnderrubric(
        string $id,
        BackpackDtoRepository $repo,
        Request $request
    )
    {
        $dto=new BackpackDto();
        $dto
            ->setCurrentState(WorkflowData::STATE_PUBLISHED)
            ->setVisible(BackpackDto::TRUE);

        $dto->setUnderRubricDto((new UnderRubricDto())->setId($id));

        return $this->search($request,$repo,$dto);
    }

    /**
     * @Route("/backpack/{id}", name="backpack_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, Rubric $item)
    {
        return $this->deleteAction($request, $item);
    }


    /**
     * @Route("/backpack/{id}/file/{fileId}", name="backpack_file_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actionFileShowAction(
        Request $request,
        Backpack $backpack,
        string $fileId,
        BackpackFileRepository $backpackFileRepository): Response
    {
        //$this->denyAccessUnlessGranted(BackpackVoter::READ, $backpack);

        $actionFile = $backpackFileRepository->find($fileId);

        $file = new File($actionFile->getHref());

        return $this->file($file, $actionFile->getTitle() . '.' . $actionFile->getFileExtension());
    }


    /**
     * @Route("/backpacks/search", name="backpacks_search", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function search(
        Request $request,
        BackpackDtoRepository $repo,
        BackpackDto $dto = null
    ){
        if ($dto->getVisible() === null && $dto->getHide() === null ) {

            $dto = new BackpackDto();
            $dto->setData($request);
        }

        $items = $repo->findAllForDto($dto,BackpackDtoRepository::FILTRE_DTO_INIT_TREE);

        $renderArray = $dto->getData();

        $tree = new BackpackTree($this->container, $request);
        $tree
            ->initialise($items)
            ->setRoute('backpacks_search')
            ->setParameter($renderArray);


        count($items)<= $this->bag->get('undevelopped_when_more') && $tree->Developed();
        array_key_exists('underRubric',$renderArray) && $tree->hideUnderThematic();

        dump($renderArray);
        $renderArray = array_merge($renderArray,
            [
                'items' => $tree->getTree(),
                'count' => $tree->getCountItems(),
                'item' => $tree->getItem()
            ]);

        return $this->render('backpack/search.html.twig', $renderArray);

    }
}