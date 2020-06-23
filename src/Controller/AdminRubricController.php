<?php

namespace App\Controller;

use App\Entity\Rubric;
use App\Form\Admin\RubricType;
use App\Manager\RubricManager;
use App\Repository\RubricRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ThematicController
 * @package App\Controller
 */
class AdminRubricController extends AbstractGController
{
    public function __construct
    (
        RubricRepository $repository,
        RubricManager $manager
    )
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'admin_rubric';
    }

    /**
     * @Route("/admin/rubric", name="admin_rubric_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
//     * @Route("/admin/rubric/sort", name="admin_rubric_sort", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function sort()
    {
        return $this->render($this->domaine . '/sort.html.twig',
            [
                'items' => $this->repository->findAllForAdmin()
            ]);
    }

    /**
     * @Route("/admin/rubric/sort/apply", name="admin_rubric_sort_apply", methods={"GET"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function sortApply(Request $request): Response
    {
        $result=$request->get('result');
        $datas=explode('_',$result);

        foreach ($datas as $key => $id){
            $thematic=$this->repository->find($id);
            $thematic->setShowOrder($key);
            $this->manager->save($thematic);
        }
        return $this->redirectToRoute('admin_rubric_list');
    }

    /**
     * @Route("/admin/rubric/add", name="admin_rubric_add", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new Rubric(), RubricType::class,false);
    }

    /**
     * @Route("/admin/rubric/{id}", name="admin_rubric_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, Rubric $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/admin/rubric/{id}", name="admin_rubric_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, Rubric $item)
    {
        return $this->showAction($request, $item);
    }


    /**
     * @Route("/admin/rubric/{id}/edit", name="admin_rubric_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function edit(Request $request, Rubric $item)
    {
        return $this->editAction($request, $item, RubricType::class);
    }
}
