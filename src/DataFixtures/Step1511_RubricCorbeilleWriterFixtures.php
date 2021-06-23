<?php

namespace App\DataFixtures;

use App\Entity\Action;
use App\Entity\Corbeille;
use App\Entity\Rubric;
use App\Helper\FixturesImportData;
use App\Repository\ActionRepository;
use App\Repository\CorbeilleRepository;
use App\Repository\RubricRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1511_RubricCorbeilleWriterFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'thematique_corbeille_modification';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var Corbeille[]
     */
    private $corbeilles;

    /**
     * @var Rubric[]
     */
    private $rubrics;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;


    public function __construct(
        FixturesImportData $fixturesImportData,
        CorbeilleRepository $corbeilleRepository,
        RubricRepository $rubricRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->corbeilles = $corbeilleRepository->findAll();
        $this->rubrics = $rubricRepository->findAll();
        $this->entityManagerInterface = $entityManagerI;
    }

    public function getInstance(string $id, $entitys)
    {
        foreach ($entitys as $entity) {
            if ($entity->getId() == $id) {
                return $entity;
            }
        }
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->fixturesImportData->importToArray(self::FILENAME . '.json');

        for ($i = 0; $i < \count($data); ++$i) {
            $corbeille = $this->getInstance($data[$i]['droite'], $this->corbeilles);
            /** @var Rubric $rubric */
            $rubric = $this->getInstance($data[$i]['gauche'], $this->rubrics);

            if (
                is_a($corbeille, Corbeille::class)
                &&
                is_a($rubric, Rubric::class)
            ) {
                if ($corbeille->getIsShowWrite()) {
                    $rubric->addWriter($corbeille);
                    $this->entityManagerInterface->persist($rubric);
                }
            }
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['step1511'];
    }
}
