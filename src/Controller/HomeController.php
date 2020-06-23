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
        RubricDtoRepository $rubricDtoRepository,
        RubricRepository $rp
    )
    {
        $rubricDto
            ->setIsEnable(RubricDto::TRUE)
            ->thematicDto->setIsEnable(RubricDto::TRUE);

        if (!$this->isgranted('ROLE_GESTIONNAIRE')) {
            $rubricDto
                ->userDto->setName($this->getUser()->getName());
        }

        return $this->render('home/index.html.twig', [
            'rubrics' => $rubricDtoRepository->findAllForDto($rubricDto, RubricDtoRepository::FILTRE_DTO_INIT_HOME)
        ]);
    }
}
