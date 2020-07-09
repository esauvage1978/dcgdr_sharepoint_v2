<?php

namespace App\Controller;

use App\Dto\RubricDto;
use App\Dto\UserDto;
use App\Helper\ParamsInServices;
use App\Repository\BackpackDtoRepository;
use App\Repository\RubricDtoRepository;
use App\Security\CurrentUser;
use App\Service\BackpackMakerDto;
use App\Tree\BackpackTree;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(
        RubricDtoRepository $repo
    )
    {
        $dto=new RubricDto ();

        $dto->setVisible(RubricDto::TRUE);

        if (!is_null($this->getUser()) && !$this->isgranted('ROLE_GESTIONNAIRE')) {
            $dto->setUserDto((new UserDto())->setId($this->getUser()->getId()));
        }

        return $this->render('home/index.html.twig', [
            'items' => $repo->findAllForDto($dto, RubricDtoRepository::FILTRE_DTO_INIT_HOME)
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
