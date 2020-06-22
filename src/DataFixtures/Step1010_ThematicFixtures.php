<?php

namespace App\DataFixtures;

use App\Entity\Thematic;
use App\Helper\FixturesImportData;
use App\Validator\ThematicValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class Step1010_ThematicFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'asr_box_thematique';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    /**
     * @var ThematicValidator
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    private $dataAdd;



    public function __construct(
        FixturesImportData $fixturesImportData,
        ThematicValidator $validator,
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
            $instance = $this->initialise(new Thematic(), $data[$i]);
            if (!empty($instance)) {
                $this->checkAndPersist($instance);
            }
        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist(Thematic $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Thematic::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
            return;
        }
            var_dump('Validator : ' . $this->validator->getErrors($instance));

    }

    private function initialise(Thematic $instance, $data): ?Thematic
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
        return ['step1010'];
    }
}
