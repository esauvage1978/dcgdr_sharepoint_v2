<?php

namespace App\DataFixtures;

use App\Entity\UnderThematic;
use App\Helper\FixturesImportData;
use App\Validator\UnderThematicValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class Step1220_UnderThematicFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'asr_box_sousthematique';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    /**
     * @var UnderThematicValidator
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    private $dataAdd;

    private $transcription;

    public function __construct(
        FixturesImportData $fixturesImportData,
        UnderThematicValidator $validator,
        EntityManagerInterface $entityManagerI
    )
    {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->entityManagerInterface = $entityManagerI;
        $this->dataAdd=[];
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->fixturesImportData->importToArray(self::FILENAME . '.json');

        for ($i = 0; $i < \count($data); ++$i) {
            $instance = $this->initialise(new UnderThematic(), $data[$i]);
            if (!empty($instance)) {
                $this->checkAndPersist($instance);
            }
        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist(UnderThematic $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(UnderThematic::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
            return;
        }
        var_dump('Validator : ' . $this->validator->getErrors($instance));

    }

    private function initialise(UnderThematic $instance, $data): ?UnderThematic
    {
        if (empty($data['motcle'])) {
            return null;
        }

        if(in_array($data['motcle'],$this->dataAdd)) {
            return null;
        }

        array_push( $this->dataAdd,$data['motcle']);

        $instance
            ->setName($data['motcle'])
            ->setIsEnable(true);

        return $instance;
    }

    public static function getGroups(): array
    {
        return ['step1220'];
    }
}
