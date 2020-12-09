<?php

namespace App\Controller;

use App\Dto\BackpackDto;
use App\Entity\Backpack;
use App\Manager\BackpackManager;
use App\Service\BackpackForTree;
use App\Service\BackpackMakerDto;
use App\Repository\BackpackRepository;
use App\Repository\BackpackDtoRepository;
use App\Service\BackpackCounter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class ThematicController
 * @package App\Controller
 */
class BackpackTreeController extends AbstractGController
{


    /**
     * @var BackpackDtoRepository
     */
    private $backpackDtoRepository;

    /**
     * @var BackpackForTree
     */
    private $backpackForTree;

    public function __construct(
        BackpackRepository $repository,
        BackpackForTree $backpackForTree,
        BackpackDtoRepository $backpackDtoRepository
    ) {
        $this->repository = $repository;
        $this->domaine = 'backpack';
        $this->backpackForTree = $backpackForTree;
        $this->backpackDtoRepository= $backpackDtoRepository;
    }



    /**
     * @Route("/backpacks/mydraft_updatable", name="backpacks_mydraft_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function backpacks_mydraft_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::MY_DRAFT_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }


    /**
     * @Route("/backpacks/draft", name="backpacks_draft", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function backpacks_draft(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::DRAFT);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/published", name="backpacks_published", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function backpacks_published(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::PUBLISHED);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/mypublished_updatable", name="backpacks_mypublished_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function backpacks_mypublished_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::MY_PUBLISHED_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }


    /**
     * @Route("/backpacks/archived", name="backpacks_archived", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function backpacks_archived(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::ARCHIVED);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/myarchived_updatable", name="backpacks_myarchived_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function backpacks_myarchived_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::MY_ARCHIVED_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/abandonned", name="backpacks_abandonned", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function backpacks_abandonned(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::ABANDONNED);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/myabandonnedupdatable", name="backpacks_myabandonned_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function myabandonned_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::MY_ABANDONNED_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks", name="backpacks", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function treeView(Request $request, BackpackDto $dto)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, null, $dto);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }


    /**
     * @Route("/underrubric/{id}", name="underrubric_show", methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function showUnderrubric(string $id, Request $request)
    {
        $dto= $this->backpackForTree->getDto(BackpackMakerDto::PUBLISHED_FOR_UNDERRUBRIC,$id);
        $renderArray = $this->backpackForTree->getDatas($this->container,$request, null, $dto);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

}