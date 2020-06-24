<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use App\Entity\Rubric;
use App\Entity\Thematic;
use App\Entity\UnderRubric;
use App\Entity\UnderThematic;
use App\Helper\FixturesImportData;
use App\Repository\PictureRepository;
use App\Repository\RubricRepository;
use App\Repository\ThematicRepository;
use App\Repository\UnderThematicRepository;
use App\Validator\RubricValidator;
use App\Validator\UnderRubricValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class Step1230_UnderRubricFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'asr_box_sousthematique';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var RubricValidator
     */
    private $validator;

    /**
     * @var Rubric[]
     */
    private $rubrics;

    /**
     * @var UnderThematic[]
     */
    private $underthematics;

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
        UnderRubricValidator $validator,
        RubricRepository $rubricRepository,
        UnderThematicRepository $underthematicRepository,
        PictureRepository $pictureRepository,
        EntityManagerInterface $entityManagerI
    )
    {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->underthematics = $underthematicRepository->findAll();
        $this->rubrics = $rubricRepository->findAll();
        $this->pictures = $pictureRepository ->findAll();
        $this->entityManagerInterface = $entityManagerI;
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->fixturesImportData->importToArray(self::FILENAME . '.json');

        for ($i = 0; $i < count($data); ++$i) {
            $instance = $this->initialise(new UnderRubric(), $data[$i]);

            $this->checkAndPersist($instance);

        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist(UnderRubric $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(UnderRubric::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
        } else {
            var_dump('Validator : ' . $this->validator->getErrors($instance));
        }
    }

    public function getInstance(string $id, $entitys)
    {
        if ($id === '0') {
            return null;
        }

        foreach ($entitys as $entity) {
            if ($entity->getId() == $id) {
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

    private function initialise(UnderRubric $instance, $data): UnderRubric
    {
        /** @var UnderThematic $underthematic */
        $underthematic = $this->getInstanceByName($data['motcle'], $this->underthematics);

        /** @var Rubric $rubric */
        $rubric = $this->getInstance($data['id_thematique'], $this->rubrics);

        /** @var Picture $picture */
        $picture = $this->getInstanceByContent($data['visuel'], $this->pictures);


        $instance
            ->setId($data['n0_num'])
            ->setName($data['nom'])
            ->setIsEnable($data['afficher'])
            ->setContent($data['description'])
            ->setShowOrder(empty($data['ordre'])?0:$data['ordre'])
            ->setIsShowAll($data['consultation_all']=='1'?true:false);

        if (!empty($rubric)) {
            $instance->setRubric($rubric);
        }

        if (!empty($underthematic)) {
            $instance->setUnderThematic($underthematic);
        }

        if (!empty($picture)) {
            $instance->setPicture($picture);
        }

        return $instance;
    }

    public static function getGroups(): array
    {
        return ['step1230'];
    }


}
