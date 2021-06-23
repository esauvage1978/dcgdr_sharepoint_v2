<?php

namespace App\Controller;

use App\Dto\RubricDto;
use App\Dto\UserDto;
use App\Helper\ParamsInServices;
use App\Repository\BackpackDtoRepository;
use App\Repository\RubricDtoRepository;
use App\Repository\UserRepository;
use App\Security\CurrentUser;
use App\Service\BackpackMakerDto;
use App\Tree\BackpackTree;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/gestionnaire", name="gestionnaire")
     */
    public function gestionnaire(UserRepository $repo)
    {
        return $this->render('contact/gestionnaire.html.twig', [
            'items' => $repo->findAllForContactGestionnaire()
        ]);
    }
    /**
     * @Route("/administrateur", name="administrateur")
     */
    public function administrateur(UserRepository $repo)
    {
        return $this->render('contact/administrateur.html.twig', [
            'items' => $repo->findAllForContactAdmin()
        ]);
    }

}
