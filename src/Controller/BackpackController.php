<?php

namespace App\Controller;

use App\Dto\BackpackDto;
use App\Entity\Backpack;
use App\Entity\Rubric;
use App\Entity\User;
use App\Form\Backpack\BackpackNewType;
use App\Form\Backpack\BackpackType;
use App\Helper\ParamsInServices;
use App\History\BackpackHistory;
use App\History\HistoryShow;
use App\Manager\BackpackManager;
use App\Repository\BackpackDtoRepository;
use App\Repository\BackpackFileRepository;
use App\Repository\BackpackRepository;
use App\Repository\UnderRubricRepository;
use App\Security\CurrentUser;
use App\Service\BackpackMakerDto;
use App\Tree\BackpackTree;
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
        $this->backpackDtoRepository=$backpackDtoRepository;
        $this->backpackMakerDto=new BackpackMakerDto($currentUser->getUser());
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
     * @Route("/backpacks/news/rubric/{id}", name="backpacks_news_for_rubric", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function newsForRubric(Request $request,string $id)
    {
        return $this->treeView($request,
            $this->backpackMakerDto->get(
                BackpackMakerDto::NEWS_FOR_RUBRIC,$id
            ));
    }

    /**
     * @Route("/backpacks/news/underrubric/{id}", name="backpacks_news_for_underrubric", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function newsForUnderRubric(Request $request,string $id)
    {
        return $this->treeView($request,
            $this->backpackMakerDto->get(
                BackpackMakerDto::NEWS_FOR_UNDERRUBRIC,$id
            ));
    }

    /**
     * @Route("/backpacks/news", name="backpacks_news", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function news(Request $request)
    {
        return $this->treeView($request,$this->backpackMakerDto->get(BackpackMakerDto::NEWS));
    }

    /**
     * @Route("/backpacks/abandonned", name="backpacks_abandonned", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_abandonned(Request $request)
    {
        return $this->treeView($request,$this->backpackMakerDto->get(BackpackMakerDto::ABANDONNED));
    }

    /**
     * @Route("/backpacks/archived", name="backpacks_archived", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_archived(Request $request)
    {
        return $this->treeView($request,$this->backpackMakerDto->get(BackpackMakerDto::ARCHIVED));
    }


    /**
     * @Route("/backpacks/draft", name="backpacks_draft", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_draft(Request $request)
    {
        return $this->treeView($request,$this->backpackMakerDto->get(BackpackMakerDto::DRAFT));
    }

    /**
     * @Route("/backpacks/published", name="backpacks_published", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_published(Request $request)
    {
        return $this->treeView($request,$this->backpackMakerDto->get(BackpackMakerDto::PUBLISHED));
    }

    /**
     * @Route("/backpacks/mydraft", name="backpacks_mydraft", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_mydraft(Request $request)
    {
        return $this->treeView($request,$this->backpackMakerDto->get(BackpackMakerDto::MY_DRAFT));
    }


    /**
     * @Route("/backpacks/hide", name="backpacks_hide", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showHide(Request $request)
    {
        return $this->treeView($request,$this->backpackMakerDto->get(BackpackMakerDto::HIDE));
    }

    /**
     * @Route("/backpack/{id}/history", name="backpack_history", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function history(Request $request,Backpack $item): Response
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
    public function showUnderrubric(string $id,Request $request)
    {
        return $this->treeView($request,$this->backpackMakerDto->get(BackpackMakerDto::PUBLISHED_FOR_UNDERRUBRIC,$id));
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
     * @Route("/backpacks", name="backpacks", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function treeView(Request $request,BackpackDto $dto = null)
    {
        if ($dto->getVisible() === null && $dto->getHide() === null) {

            $dto->setData($request);
        }

        if(null===$dto) {
            $items=null;
        } else {
            $items = $this->backpackDtoRepository->findAllForDto($dto, BackpackDtoRepository::FILTRE_DTO_INIT_TREE);
        }

        $renderArray = $dto->getData();

        $tree = new BackpackTree($this->container, $request,$this->paramsInServices);
        $tree
            ->initialise($items)
            ->setRoute('backpacks')
            ->setParameter($renderArray);


        count($items) <= $this->paramsInServices->get(ParamsInServices::TREE_UNDEVELOPPED_FOR_NBR) && $tree->Developed();
        array_key_exists('underRubric', $renderArray) && $tree->hideUnderThematic();

        $renderArray = array_merge($renderArray,
            [
                'items' => $tree->getTree(),
                'count' => $tree->getCountItems(),
                'item' => $tree->getItem()
            ]);

        return $this->render('backpack/tree.html.twig', $renderArray);

    }

    /**
     * @Route("/backpacks/search", name="backpacks_search", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function homeSearchAction(Request $request): Response
    {
        $r=$request->get('r');
        return $this->treeView($request,$this->backpackMakerDto->get(BackpackMakerDto::PUBLISHED_FOR_SEARCH,$r));
    }
}