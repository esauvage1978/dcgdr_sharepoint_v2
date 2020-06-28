<?php

namespace App\Controller;

use App\Dto\RubricDto;
use App\Dto\ThematicDto;
use App\Dto\UserDto;
use App\Repository\MessageRepository;
use App\Repository\RubricDtoRepository;
use App\Repository\RubricRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
