<?php

namespace App\Controller;

use App\Entity\UnderRubric;
use App\Form\Admin\UnderRubricType;
use App\Manager\UnderRubricManager;
use App\Repository\UnderRubricRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ThematicController
 * @package App\Controller
 */
class AdminUnderRubricController extends AbstractGController
{
    public function __construct
    (
        UnderRubricRepository $repository,
        UnderRubricManager $manager
    )
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'admin_underrubric';
    }

    /**
     * @Route("/admin/underrubric", name="admin_underrubric_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
//     * @Route("/admin/underrubric/sort", name="admin_underrubric_sort", methods={"GET"})
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
     * @Route("/admin/underrubric/sort/apply", name="admin_underrubric_sort_apply", methods={"GET"})
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
        return $this->redirectToRoute('admin_underrubric_list');
    }

    /**
     * @Route("/admin/underrubric/add", name="admin_underrubric_add", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new UnderRubric(), UnderRubricType::class,false);
    }

    /**
     * @Route("/admin/underrubric/{id}", name="admin_underrubric_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, UnderRubric $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/admin/underrubric/{id}", name="admin_underrubric_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, UnderRubric $item)
    {
        return $this->showAction($request, $item);
    }


    /**
     * @Route("/admin/underrubric/{id}/edit", name="admin_underrubric_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function edit(Request $request, UnderRubric $item)
    {
        return $this->editAction($request, $item, UnderRubricType::class);
    }
}
