<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UserDto;
use App\Entity\User;
use App\Dto\RubricDto;
use App\Security\Role;
use App\Dto\ProcessDto;
use App\Dto\MProcessDto;
use App\Entity\Backpack;
use App\Entity\Category;
use App\Dto\UnderRubricDto;
use App\Service\BackpackRefGenerator;
use App\Repository\BackpackRepository;
use App\Controller\AbstractGController;
use App\Repository\RubricDtoRepository;
use App\Repository\ProcessDtoRepository;
use App\Service\BackpackRefControllator;
use App\Repository\MProcessDtoRepository;
use App\Repository\UnderRubricDtoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AjaxBackpackController extends AbstractGController
{

    /**
     * @Route("/ajax/backpack/{id}", name="ajax_backpack_get_ref", methods={"GET","POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxBackpackGetData(Request $request, BackpackRepository $backpackRepository, Backpack $backpack): Response
    {

        return $this->json([
            'code' => 200,
            'value' => $this->renderView('backpack/_show/_treeContent.html.twig', ['item' => $backpack]),
            'message' => 'données transmises'
        ], 200);
    }

    /**
     * @Route("/ajax/getrubrics", name="ajax_fill_combobox_rubrics", methods={"GET","POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetRubrics(Request $request, RubricDtoRepository $rubricDtoRepository): Response
    {
        $dto = new RubricDto();
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $dto
            ->setForUpdate(RubricDto::TRUE)
            ->setVisible(RubricDto::TRUE);

        if (!Role::isGestionnaire($user)) {
            $dto->setUserDto((new UserDto())->setId($this->getUser()->getId()));
        }

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
        UnderRubricDtoRepository $underrubricDtoRepository
    ): Response {
        $idRubric = $request->get('id');
        $dto = new UnderRubricDto();
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $dto
            ->setForUpdate(RubricDto::TRUE)
            ->setRubricDto((new RubricDto())
                    ->setId($idRubric)
            )
            ->setVisible(RubricDto::TRUE);

        if (!Role::isGestionnaire($user)) {
            $dto->setUserDto((new UserDto())->setId($this->getUser()->getId()));
        }

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
        $data = null;
        if ($request->request->has('id')) {
            $data = $request->request->get('id');
        }
        if ($request->isXmlHttpRequest()) {
            return $this->json(
                $repository->findAllFillComboboxDir1(
                    $data
                )
            );
        }

        return new Response("Ce n'est pas une requête Ajax");
    }
}
