<?php

namespace App\Controller;

use App\Dto\RubricDto;
use App\Dto\UnderRubricDto;
use App\Entity\Rubric;
use App\Form\Admin\RubricType;
use App\Manager\RubricManager;
use App\Repository\RubricDtoRepository;
use App\Repository\RubricRepository;
use App\Repository\UnderRubricDtoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ThematicController
 * @package App\Controller
 */
class RubricController extends AbstractGController
{
    public function __construct
    (
        RubricRepository $repository,
        RubricManager $manager
    )
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'rubric';
    }

    /**
     * @Route("/rubric/{id}", name="rubric_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(
        UnderRubricDto $dto,
        UnderRubricDtoRepository $rubricDtoRepository,
        Rubric $item
    ) {
        $dto
            ->setIsEnable(UnderRubricDto::TRUE)
            ->rubricDto->setIsEnable(UnderRubricDto::TRUE)
            ->setId($item->getId())
            ->thematicDto->setIsEnable(UnderRubricDto::TRUE);
        $dto
            ->underThematicDto->setIsEnable(UnderRubricDto::TRUE);

        if (!is_null($this->getUser()) && !$this->isgranted('ROLE_GESTIONNAIRE')) {
            $dto
                ->userDto->setName($this->getUser()->getName());
        }

        return $this->render('rubric/index.html.twig', [
            'items' => $rubricDtoRepository->findAllForDto($dto, UnderRubricDtoRepository::FILTRE_DTO_INIT_HOME),
            'item' => $item
        ]);
    }
}