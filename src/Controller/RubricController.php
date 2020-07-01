<?php

namespace App\Controller;

use App\Dto\RubricDto;
use App\Dto\ThematicDto;
use App\Dto\UnderRubricDto;
use App\Dto\UnderThematicDto;
use App\Dto\UserDto;
use App\Entity\Rubric;
use App\Manager\RubricManager;
use App\Repository\RubricRepository;
use App\Repository\UnderRubricDtoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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

        UnderRubricDtoRepository $rubricDtoRepository,
        Rubric $item
    )
    {
        $dto = new UnderRubricDto();
        $dto
            ->setVisible(UnderRubricDto::TRUE)
            ->setRubricDto((new RubricDto())->setId($item->getId()));

        if (!is_null($this->getUser()) && !$this->isgranted('ROLE_GESTIONNAIRE')) {
            $dto->setUserDto(
                (new UserDto())
                    ->setId($this->getUser()->getName())
            );
        }

        return $this->render('rubric/index.html.twig', [
            'items' => $rubricDtoRepository->findAllForDto($dto, UnderRubricDtoRepository::FILTRE_DTO_INIT_HOME),
            'item' => $item
        ]);
    }
}