<?php

namespace App\DataFixtures;

use App\Entity\Corbeille;
use App\Helper\FixturesImportData;
use App\Repository\OrganismeRepository;
use App\Repository\UserRepository;
use App\Validator\CorbeilleValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1131_CorbeilleFixtures extends Fixture implements  FixtureGroupInterface
{
    CONST FILENAME = 'corbeille';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    /**
     * @var CorbeilleValidator
     */
    private $validator;

    /**
     * @var \App\Entity\Organisme[]
     */
    private $organismes;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        CorbeilleValidator $validator,
        OrganismeRepository $organismeRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->organismes = $organismeRepository->findAll();
        $this->entityManagerInterface=$entityManagerI;
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
        $data = $this->fixturesImportData->importToArray(self::FILENAME . ".json");

        for ($i = 0; $i < \count( $data ); $i++) {

            $instance = $this->initialise(new Corbeille(), $data[$i]);

            $this->checkAndPersist($instance);
        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist(Corbeille $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Corbeille::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
        } else {
            var_dump('Validator : ' . $this->validator->getErrors($instance));
        }
    }

    private function initialise(Corbeille $instance, $data): Corbeille
    {
        $organisme = $this->getInstance($data['id_organisme'], $this->organismes);

        $instance
            ->setId($data['n0_num'])
            ->setName(
                strlen($data['nom']) > 3 ?
                    $data['nom'] :
                    $data['nom'] . '_fixtures')
            ->setIsEnable($data['afficher'])
            ->setContent($data['description'])
            ->setIsShowRead($data['pa_lecture'])
            ->setIsShowWrite($data['pa_ecriture'])
            ->setOrganisme($organisme);

        return $instance;
    }

    public static function getGroups(): array
    {
        return ['step1131'];
    }
}
