<?php

namespace App\Controller;

use App\Entity\Corbeille;
use App\Form\Admin\CorbeilleType;
use App\Manager\CorbeilleManager;
use App\Repository\CorbeilleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CorbeilleController
 * @package App\Controller
 * @route("/corbeille")
 */
class CorbeilleController extends AbstractGController
{
    public function __construct
    (
        CorbeilleRepository $repository,
        CorbeilleManager $manager
    )
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'corbeille';
    }

    /**
     * @Route("/", name="corbeille_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/add", name="corbeille_add", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new Corbeille(), CorbeilleType::class,false);
    }

    /**
     * @Route("/{id}", name="corbeille_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, Corbeille $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/{id}", name="corbeille_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, Corbeille $item)
    {
        return $this->showAction($request, $item);
    }


    /**
     * @Route("/{id}/edit", name="corbeille_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function edit(Request $request, Corbeille $item)
    {
        return $this->editAction($request, $item, CorbeilleType::class);
    }
}
