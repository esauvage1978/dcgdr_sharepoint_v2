<?php

namespace App\Controller;

use App\Dto\BackpackDto;
use App\Dto\RubricDto;
use App\Dto\ThematicDto;
use App\Dto\UnderRubricDto;
use App\Dto\UnderThematicDto;
use App\Dto\UserDto;
use App\Entity\Backpack;
use App\Entity\Rubric;
use App\Entity\UnderRubric;
use App\Form\Backpack\BackpackNewType;
use App\Form\Backpack\BackpackType;
use App\Manager\BackpackManager;
use App\Repository\BackpackDtoRepository;
use App\Repository\BackpackFileRepository;
use App\Repository\BackpackRepository;
use App\Repository\UnderRubricRepository;
use App\Security\BackpackVoter;
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

    public function __construct
    (
        BackpackRepository $repository,
        backpackManager $manager,
        UnderRubricRepository $underRubricRepository
    )
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'backpack';
        $this->underRubricRepository=$underRubricRepository;
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
    public function edit(Request $request, Backpack $item)
    {

        //$this->denyAccessUnlessGranted(BackpackVoter::UPDATE, $backpack);
        $backpackOld = clone($item);
        $form = $this->createForm(BackpackType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->save($item)
                ?
                $this->addFlash(self::SUCCESS, self::MSG_MODIFY)
                :
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($item));
        }

        return $this->render('backpack/edit.html.twig', [
            'item' => $item,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @Route("/backpacks/abandonned", name="backpacks_show_abandonned", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_abandonned(
        Request $request,
        BackpackDtoRepository $repo
    )
    {
        return $this->redirectToRoute('backpacks_show_state', ['state' => WorkflowData::STATE_ABANDONNED]);
    }

    /**
     * @Route("/backpacks/archived", name="backpacks_show_archived", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_archived(
        Request $request,
        BackpackDtoRepository $repo
    )
    {
        return $this->redirectToRoute('backpacks_show_state', ['state' => WorkflowData::STATE_ARCHIVED]);
    }

    /**
     * @Route("/backpacks/draft", name="backpacks_show_draft", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_draft(
        Request $request,
        BackpackDtoRepository $repo
    )
    {
        return $this->redirectToRoute('backpacks_show_state', ['state' => WorkflowData::STATE_DRAFT]);
    }

    /**
     * @Route("/backpacks/mydraft", name="backpacks_show_mydraft", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_mydraft(
        Request $request,
        BackpackDtoRepository $repo
    )
    {
        return $this->show($request, $repo, WorkflowData::STATE_DRAFT, true);
    }

    /**
     * @Route("/backpacks/state/{state}", name="backpacks_show_state", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(
        Request $request,
        BackpackDtoRepository $repo,
        string $state,
        bool $owner = false,
        UnderRubric $underRubric = null
    )
    {
        $owner = $request->get('owner') ? $request->get('owner') : $owner;
        $urId = $request->get('urId');
        $items = $this->getItems($state, $owner, $repo, $urId);

        if (is_null($underRubric) && !empty($urId)) {
            $underRubric = $this->underRubricRepository->find($urId);
        }

        $parameter =
            [
                'state' => $state,
                'owner' => $owner,
                'urId' => $urId
            ];

        $renderArray = $parameter;

        $tree = new BackpackTree($this->container, $request);
        $tree
            ->initialise($items)
            ->setRoute('backpacks_show_state')
            ->setParameter($parameter);

        if (!is_null($urId)) {
            $tree->hideUnderThematic();
        }

        $renderArray = array_merge($renderArray,
            [
                'items' => $tree->getTree(),
                'count' => $tree->getCountItems(),
                'item' => $tree->getItem(),
                'underrubric' => $underRubric
            ]);

        return $this->render('backpack/state.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/hide", name="backpacks_show_hide", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showHide(
        Request $request,
        BackpackDtoRepository $repo
    ) {
        $dto = new BackpackDto();
        $dto->setHide(BackpackDto::TRUE);

        if (!is_null($this->getUser()) && !$this->isgranted('ROLE_GESTIONNAIRE')) {
            $dto->setUserDto((new UserDto())->setId($this->getUser()->getName()));
        }

        $items= $repo->findAllForDto($dto, BackpackDtoRepository::FILTRE_DTO_INIT_HOME);

        $parameter =
            [
            ];

        $renderArray = $parameter;

        $tree = new BackpackTree($this->container, $request);
        $tree
            ->initialise($items)
            ->setRoute('backpacks_show_hide')
            ->setParameter($parameter);


        $renderArray = array_merge($renderArray,
            [
                'items' => $tree->getTree(),
                'count' => $tree->getCountItems(),
                'item' => $tree->getItem()
            ]);

        return $this->render('backpack/hide.html.twig', $renderArray);
    }

    /**
     * @Route("/underrubric/{id}", name="underrubric_show", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function showUnderrubric(
        UnderRubric $underRubric,
        BackpackDtoRepository $repo,
        Request $request
    )
    {
        $request->request->add(['urId' => $underRubric->getId()]);
        return $this->show($request, $repo, WorkflowData::STATE_PUBLISHED, false, $underRubric);
    }

    /**
     * @Route("/backpack/{id}", name="backpack_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, Rubric $item)
    {
        return $this->deleteAction($request, $item);
    }

    public function getItems($state, $owner, BackpackDtoRepository $repo, $underrubric)
    {
        $dto = new BackpackDto();
        $dto
            ->setCurrentState($state)
            ->setVisible(BackpackDto::TRUE);

        if (!is_null($this->getUser()) && !$this->isgranted('ROLE_GESTIONNAIRE')) {
            $dto->setUserDto((new UserDto())->setId($this->getUser()->getName()));
        }

        if (!is_null($underrubric)) {
            if(is_null($dto->getUnderRubricDto())) {
                $dto->setUnderRubricDto((new UnderRubricDto())->setId($underrubric));
            } else {
                $dto->setUnderRubricDto($dto->getUnderRubricDto()->setId($underrubric));
            }
        }

        if (!is_null($this->getUser()) && $owner == true) {
            $dto->setOwnerDto((new UserDto())->setId($this->getUser()->getId()));
        }

        return $repo->findAllForDto($dto, BackpackDtoRepository::FILTRE_DTO_INIT_HOME);
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
}