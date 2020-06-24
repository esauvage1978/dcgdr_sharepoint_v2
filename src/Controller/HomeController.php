<?php

namespace App\Controller;

use App\Dto\RubricDto;
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
        RubricDto $rubricDto,
        RubricDtoRepository $rubricDtoRepository
    )
    {
        $rubricDto
            ->setIsEnable(RubricDto::TRUE)
            ->thematicDto->setIsEnable(RubricDto::TRUE);

        if (!is_null($this->getUser()) && !$this->isgranted('ROLE_GESTIONNAIRE')) {
            $rubricDto
                ->userDto->setName($this->getUser()->getName());
        }

        return $this->render('home/index.html.twig', [
            'items' => $rubricDtoRepository->findAllForDto($rubricDto, RubricDtoRepository::FILTRE_DTO_INIT_HOME)
        ]);
    }
}
