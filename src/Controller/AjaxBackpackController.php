<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UserDto;
use App\Entity\User;
use App\Dto\ProcessDto;
use App\Dto\MProcessDto;
use App\Entity\Backpack;
use App\Entity\Category;
use App\Service\BackpackRefGenerator;
use App\Repository\BackpackRepository;
use App\Repository\ProcessDtoRepository;
use App\Repository\MProcessDtoRepository;
use App\Service\BackpackRefControllator;
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


}
