<?php

namespace App\DataFixtures;

use App\Entity\Corbeille;
use App\Entity\UnderRubric;
use App\Helper\FixturesImportData;
use App\Repository\CorbeilleRepository;
use App\Repository\UnderRubricRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1512_UnderRubricCorbeilleReaderFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'sousthematique_corbeille_consultation';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var Corbeille[]
     */
    private $corbeilles;

    /**
     * @var UnderRubric[]
     */
    private $underrubrics;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;


    public function __construct(
        FixturesImportData $fixturesImportData,
        CorbeilleRepository $corbeilleRepository,
        UnderRubricRepository $underrubricRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->corbeilles = $corbeilleRepository->findAll();
        $this->underrubrics = $underrubricRepository->findAll();
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
        $data = $this->fixturesImportData->importToArray(self::FILENAME.'.json');

        for ($i = 0; $i < \count($data); ++$i) {
            $corbeille = $this->getInstance($data[$i]['droite'], $this->corbeilles);
            /** @var UnderRubric $underrubric */
            $underrubric = $this->getInstance($data[$i]['gauche'], $this->underrubrics);

            if (is_a($corbeille, Corbeille::class)
                &&
                is_a($underrubric, UnderRubric::class)
            ) {
                if ($corbeille->getIsShowRead()) {
                    $underrubric->addReader($corbeille);
                    $this->entityManagerInterface->persist($underrubric);
                }
            }


        }

        $manager->flush();


    }

    public static function getGroups(): array
    {
        return ['step1512'];
    }
}
