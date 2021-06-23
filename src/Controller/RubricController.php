<?php

namespace App\Controller;

use App\Dto\UserDto;
use App\Dto\RubricDto;
use App\Entity\Rubric;
use App\Dto\ThematicDto;
use App\Dto\UnderRubricDto;
use App\Dto\UnderThematicDto;
use App\Manager\RubricManager;
use App\Helper\UserByCorbeilles;
use App\Repository\RubricRepository;
use App\Repository\UnderRubricDtoRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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
            ->setRubricDto((new RubricDto())->setId($item->getId()))
            ->setVisible(UnderRubricDto::TRUE)
            ;

        if (!is_null($this->getUser()) && !$this->isgranted('ROLE_GESTIONNAIRE')) {
            $dto->setUserDto((new UserDto())->setId($this->getUser()->getId()));
        }

        return $this->render('rubric/index.html.twig', [
            'items' => $rubricDtoRepository->findAllForDto($dto, UnderRubricDtoRepository::FILTRE_DTO_INIT_HOME),
            'item' => $item
        ]);
    }

    /**
     * @Route("/rubric/{id}/who", name="rubric_who", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function who(
        Rubric $item,
        UserByCorbeilles $userByCorbeilles
    ) {
        return $this->render('rubric/who.html.twig', [
            'item' => $item,
            'writers'=> $userByCorbeilles->getUsers($item->getWriters()),
            'readers' => $userByCorbeilles->getUsers($item->getReaders()),
        ]);
    }
}