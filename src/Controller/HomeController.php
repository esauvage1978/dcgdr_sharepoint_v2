<?php

namespace App\Controller;

use App\Dto\UserDto;
use App\Dto\RubricDto;
use App\Tree\BackpackTree;
use App\Security\CurrentUser;
use App\Service\RubricMakerDto;
use App\Helper\ParamsInServices;
use App\Service\BackpackMakerDto;
use App\Repository\RubricDtoRepository;
use App\Repository\BackpackDtoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(
        RubricDtoRepository $repo, CurrentUser $user
    )
    {
        $m = new RubricMakerDto($user->getUser());
        
        return $this->render('home/index.html.twig', [
            'items' => $repo->findAllForDto($m->get(RubricMakerDto::HOME))
        ]);
    }

    /**
     * @return Response
     */
    public function searchFormAction(): Response
    {
        return $this->render('home/search-form.html.twig', []);
    }

    /**
     * @return Response
     */
    public function message(): Response
    {
        return $this->render('home/search-form.html.twig', []);
    }

}
