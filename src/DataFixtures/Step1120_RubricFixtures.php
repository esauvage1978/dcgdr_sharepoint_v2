<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use App\Entity\Rubric;
use App\Entity\Thematic;
use App\Helper\FixturesImportData;
use App\Repository\PictureRepository;
use App\Repository\ThematicRepository;
use App\Validator\RubricValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class Step1120_RubricFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'asr_box_thematique';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var RubricValidator
     */
    private $validator;

    /**
     * @var Thematic[]
     */
    private $thematics;

    /**
     * @var Picture[]
     */
    private $pictures;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        RubricValidator $validator,
        ThematicRepository $thematicRepository,
        PictureRepository $pictureRepository,
        EntityManagerInterface $entityManagerI
    )
    {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->thematics = $thematicRepository->findAll();
        $this->pictures = $pictureRepository ->findAll();
        $this->entityManagerInterface = $entityManagerI;
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->fixturesImportData->importToArray(self::FILENAME . '.json');

        for ($i = 0; $i < count($data); ++$i) {
            $instance = $this->initialise(new Rubric(), $data[$i]);

            $this->checkAndPersist($instance);

        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist(Rubric $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Rubric::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
        } else {
            var_dump('Validator : ' . $this->validator->getErrors($instance));
        }
    }

    public function getInstanceByName(?string $name, $entitys)
    {
        if (empty($name)) {
            return null;
        }

        foreach ($entitys as $entity) {
            if ($entity->getName() === $name) {
                return $entity;
            }
        }
    }

    public function getInstanceByContent(?string $name, $entitys)
    {
        if (empty($name)) {
            return null;
        }

        foreach ($entitys as $entity) {
            if ($entity->getContent() === $name) {
                return $entity;
            }
        }
    }

    private function initialise(Rubric $instance, $data): Rubric
    {
        /** @var Thematic $thematic */
        $thematic = $this->getInstanceByName($data['motcle'], $this->thematics);

        /** @var Picture $picture */
        $picture = $this->getInstanceByContent($data['visuel'], $this->pictures);


        $instance
            ->setId($data['n0_num'])
            ->setName($data['nom'])
            ->setEnable($data['afficher'])
            ->setContent($data['description'])
            ->setShowOrder($data['ordre'])
            ->setShowAll($data['consultation_all']=='1'?true:false);

        if (!empty($thematic)) {
            $instance->setThematic($thematic);
        }

        if (!empty($picture)) {
            $instance->setPicture($picture);
        }

        return $instance;
    }

    public static function getGroups(): array
    {
        return ['step1120'];
    }


}
