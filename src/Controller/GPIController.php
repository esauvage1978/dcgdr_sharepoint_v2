<?php

namespace App\Controller;

use App\Entity\GPI;
use App\Form\Admin\GPIType;
use App\GPI\GPIPage;
use App\Manager\GPIManager;
use App\Repository\GPIRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GPIController
 * @package App\Controller
 * @route("/gpi")
 */
class GPIController extends AbstractGController
{
    const DOMAINE = 'gpi';

    public function __construct
    (
        GPIRepository $repository,
        GPIManager $manager
    )
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'gpi';
    }


    /**
     * @return Response
     */
    public function showGPIAction(string $page): Response
    {
        GPIPage::checkData($page);
        return $this->render('gpi/showGPI.html.twig', ['items' => $this->repository->findAllGpi($page)]);
    }

    /**
     * @Route("/", name="gpi_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/add", name="gpi_add", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new GPI(), GPIType::class, false);
    }

    /**
     * @Route("/{id}", name="gpi_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, GPI $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/{id}", name="gpi_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, GPI $item)
    {
        return $this->showAction($request, $item);
    }


    /**
     * @Route("/{id}/edit", name="gpi_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function edit(Request $request, GPI $item)
    {
        return $this->editAction($request, $item, GPIType::class);
    }
}
