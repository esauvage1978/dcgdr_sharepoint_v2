<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Form\Admin\PictureType;
use App\Manager\PictureManager;
use App\Repository\PictureRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PictureController
 * @package App\Controller
 * @route("/picture")
 */
class PictureController extends AbstractGController
{
    public function __construct
    (
        PictureRepository $repository,
        PictureManager $manager
    )
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'picture';
    }

    /**
     * @Route("/", name="picture_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/add", name="picture_add", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new Picture(), PictureType::class,false);
    }

    /**
     * @Route("/{id}", name="picture_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, Picture $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/{id}", name="picture_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, Picture $item)
    {
        return $this->showAction($request, $item);
    }


    /**
     * @Route("/{id}/edit", name="picture_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function edit(Request $request, Picture $item)
    {
        return $this->editAction($request, $item, PictureType::class);
    }
}
