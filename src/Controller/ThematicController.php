<?php

namespace App\Controller;

use App\Entity\Thematic;
use App\Form\Admin\ThematicType;
use App\Manager\ThematicManager;
use App\Repository\BackpackRepository;
use App\Repository\ThematicRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ThematicController
 * @package App\Controller
 * @route("/thematic")
 */
class ThematicController extends AbstractGController
{
    public function __construct
    (
        ThematicRepository $repository,
        ThematicManager $manager
    )
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'thematic';
    }

    /**
     * @Route("/", name="thematic_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/sort", name="thematic_sort", methods={"GET"})
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
     * @Route("/sort/apply", name="thematic_sort_apply", methods={"GET"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function sortApply(Request $request, ThematicRepository $repository, ThematicManager $manager): Response
    {
        $result=$request->get('result');
        $datas=explode('_',$result);

        foreach ($datas as $key => $id){
            $thematic=$repository->find($id);
            $thematic->setShowOrder($key);
            $manager->save($thematic);
        }
        return $this->list();
    }

    /**
     * @Route("/add", name="thematic_add", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new Thematic(), ThematicType::class,false);
    }

    /**
     * @Route("/{id}", name="thematic_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, Thematic $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/{id}", name="thematic_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, Thematic $item)
    {
        return $this->showAction($request, $item);
    }


    /**
     * @Route("/{id}/edit", name="thematic_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function edit(Request $request, Thematic $item)
    {
        return $this->editAction($request, $item, ThematicType::class);
    }
}
