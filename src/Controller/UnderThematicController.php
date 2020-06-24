<?php

namespace App\Controller;

use App\Entity\UnderThematic;
use App\Form\Admin\UnderThematicType;
use App\Manager\UnderThematicManager;
use App\Repository\UnderThematicRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ThematicController
 * @package App\Controller
 * @route("/underthematic")
 */
class UnderThematicController extends AbstractGController
{
    public function __construct
    (
        UnderThematicRepository $repository,
        UnderThematicManager $manager
    )
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'underthematic';
    }

    /**
     * @Route("/", name="underthematic_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/sort", name="underthematic_sort", methods={"GET"})
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
     * @Route("/sort/apply", name="underthematic_sort_apply", methods={"GET"})
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
        return $this->list();
    }

    /**
     * @Route("/add", name="underthematic_add", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new UnderThematic(), UnderThematicType::class,false);
    }

    /**
     * @Route("/{id}", name="underthematic_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, UnderThematic $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/{id}", name="underthematic_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, UnderThematic $item)
    {
        return $this->showAction($request, $item);
    }


    /**
     * @Route("/{id}/edit", name="underthematic_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function edit(Request $request, UnderThematic $item)
    {
        return $this->editAction($request, $item, UnderThematicType::class);
    }
}
