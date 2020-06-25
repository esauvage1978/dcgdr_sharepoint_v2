<?php

namespace App\Controller;

use App\Controller\AbstractGController;
use App\Dto\RubricDto;
use App\Dto\ThematicDto;
use App\Dto\UnderRubricDto;
use App\Dto\UnderThematicDto;
use App\Dto\UserDto;
use App\Repository\BackpackRepository;
use App\Repository\RubricDtoRepository;
use App\Repository\RubricRepository;
use App\Repository\UnderRubricDtoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FillComboboxController extends AbstractGController
{
    /**
     * @Route("/ajax/getrubrics", name="ajax_fill_combobox_rubrics", methods={"GET","POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetRubrics(Request $request, RubricDtoRepository $rubricDtoRepository): Response
    {
        $dto=new RubricDto ();

        $dto->setIsEnable(RubricDto::TRUE)
            ->setThematicDto((new ThematicDto())->setIsEnable(RubricDto::TRUE))
            ->setUnderThematicDto((new UnderThematicDto())->setIsEnable(RubricDto::TRUE))
            ->setUnderRubricDto((new UnderRubricDto())->setIsEnable(RubricDto::TRUE))
            ->setUserDto((new UserDto())->setId($this->getUser()->getId()));

        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $rubricDtoRepository->findForCombobox($dto)
            );
        }

        return new Response("Ce n'est pas une requête Ajax");
    }
    /**
     * @Route("/ajax/getunderrubrics", name="ajax_fill_combobox_underrubrics", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetUnderRubrics(
        Request $request,
        UnderRubricDtoRepository $underrubricDtoRepository): Response
    {
        $idRubric=$request->get('id');
        $dto=new UnderRubricDto ();

        $dto->setIsEnable(RubricDto::TRUE)
            ->setThematicDto((new ThematicDto())->setIsEnable(RubricDto::TRUE))
            ->setUnderThematicDto((new UnderThematicDto())->setIsEnable(RubricDto::TRUE))
            ->setRubricDto((new RubricDto())
                ->setIsEnable(RubricDto::TRUE)
                ->setId($idRubric)
            )
            ->setUserDto((new UserDto())->setId($this->getUser()->getId()));

        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $underrubricDtoRepository->findForCombobox($dto)
            );
        }

        return new Response("Ce n'est pas une requête Ajax");
    }

    /**
     * @Route("/ajax/getdir1", name="ajax_fill_combobox_dir1", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetDir1(Request $request, BackpackRepository $repository): Response
    {
        $data=null;
        if($request->request->has('id')) {
            $data=$request->request->get('id');
        }
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $repository->findAllFillComboboxDir1(
                    $data)
                );
        }

        return new Response("Ce n'est pas une requête Ajax");
    }
    /**
     * @Route("/ajax/getdir2", name="ajax_fill_combobox_dir2", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetDir2(Request $request, BackpackRepository $repository): Response
    {
        $id=null;
        $data=null;
        if($request->request->has('id')) {
            $id=$request->request->get('id');
        }

        if($request->request->has('data')) {
            $data=$request->request->get('data');
        }
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $repository->findAllFillComboboxDir2(
                    $id,$data)
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
        $id=null;
        $data=null;
        if($request->request->has('id')) {
            $id=$request->request->get('id');
        }

        if($request->request->has('data')) {
            $data=$request->request->get('data');
        }
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $repository->findAllFillComboboxDir3(
                    $id,$data)
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
        $id=null;
        $data=null;
        if($request->request->has('id')) {
            $id=$request->request->get('id');
        }

        if($request->request->has('data')) {
            $data=$request->request->get('data');
        }
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $repository->findAllFillComboboxDir4(
                    $id,$data)
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
        $id=null;
        $data=null;
        if($request->request->has('id')) {
            $id=$request->request->get('id');
        }

        if($request->request->has('data')) {
            $data=$request->request->get('data');
        }
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $repository->findAllFillComboboxDir5(
                    $id,$data)
            );
        }

        return new Response("Ce n'est pas une requête Ajax");
    }
}
