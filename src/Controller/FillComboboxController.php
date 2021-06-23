<?php

namespace App\Controller;

use App\Dto\RubricDto;
use App\Dto\UnderRubricDto;
use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\BackpackRepository;
use App\Repository\RubricDtoRepository;
use App\Repository\UnderRubricDtoRepository;
use App\Security\Role;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FillComboboxController extends AbstractGController
{ 





    /**
     * @Route("/ajax/getdir2", name="ajax_fill_combobox_dir2", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetDir2(Request $request, BackpackRepository $repository): Response
    {
        $id = null;
        $data = null;
        if ($request->request->has('id')) {
            $id = $request->request->get('id');
        }

        if ($request->request->has('data')) {
            $data = $request->request->get('data');
        }
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $repository->findAllFillComboboxDir2(
                    $id, $data)
            );
        }

        return new Response("Ce n'est pas une requête Ajax");
    }

    /**
     * @Route("/ajax/getdir3", name="ajax_fill_combobox_dir3", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetDir3(Request $request, BackpackRepository $repository): Response
    {
        $id = null;
        $data = null;
        if ($request->request->has('id')) {
            $id = $request->request->get('id');
        }

        if ($request->request->has('data')) {
            $data = $request->request->get('data');
        }
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $repository->findAllFillComboboxDir3(
                    $id, $data)
            );
        }

        return new Response("Ce n'est pas une requête Ajax");
    }

    /**
     * @Route("/ajax/getdir4", name="ajax_fill_combobox_dir4", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetDir4(Request $request, BackpackRepository $repository): Response
    {
        $id = null;
        $data = null;
        if ($request->request->has('id')) {
            $id = $request->request->get('id');
        }

        if ($request->request->has('data')) {
            $data = $request->request->get('data');
        }
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $repository->findAllFillComboboxDir4(
                    $id, $data)
            );
        }

        return new Response("Ce n'est pas une requête Ajax");
    }

    /**
     * @Route("/ajax/getdir5", name="ajax_fill_combobox_dir5", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetDir5(Request $request, BackpackRepository $repository): Response
    {
        $id = null;
        $data = null;
        if ($request->request->has('id')) {
            $id = $request->request->get('id');
        }

        if ($request->request->has('data')) {
            $data = $request->request->get('data');
        }
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $repository->findAllFillComboboxDir5(
                    $id, $data)
            );
        }

        return new Response("Ce n'est pas une requête Ajax");
    }
}
