<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use App\Helper\FileDirectory;
use App\Helper\FixturesImportData;
use App\Helper\ImageResize;
use App\Validator\PictureValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Step1111_PictureFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'asr_box_sousthematique';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var PictureValidator
     */
    private $validator;

    /**
     * @var FileDirectory
     */
    private $fileDirectory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * @var ImageResize
     */
    private $imageResize;

    public function __construct(
        FixturesImportData $fixturesImportData,
        PictureValidator $validator,
        EntityManagerInterface $entityManagerI,
        ParameterBagInterface $params
    )
    {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->entityManagerInterface = $entityManagerI;
        $this->fileDirectory = new FileDirectory();
        $this->params = $params;
        $this->imageResize=new ImageResize($params);
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->fixturesImportData->importToArray(self::FILENAME . '.json');

        for ($i = 0; $i < count($data); ++$i) {
            $instance = $this->initialise(new Picture(), $data[$i]);

            if(!empty($instance)) {
                $this->checkAndPersist($instance);
            }
        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist(Picture $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Picture::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
        } else {
            var_dump('Validator : ' . $this->validator->getErrors($instance));
        }
    }


    private function initialise(Picture $instance, $data): ?Picture
    {

        $dataFile = explode('.', $data['visuel']);

        $instance
            ->setName($data['nom'])
            ->setIsEnable(true)
            ->setContent($data['visuel']);

        if (count($dataFile) == 2) {
            $instance
                ->setName('SR_'.$data['nom'])
                ->setIsEnable(true)
                ->setContent($data['visuel'])
                ->setFileName(md5(uniqid()))
                ->setFileExtension($dataFile[1]);
                dump($data['visuel']);
                $this->moveFile($data['visuel'], $instance->getFullName());
        } else {
            return null;
        }

        return $instance;
    }

    private function moveFile(string $fileNameSource, string $fileNameDestination)
    {
        $dirDestination=$this->params->get('directory_picture');
        $dirSource=$this->params->get('directory_data_picture');

        $this->fileDirectory->moveFile($dirSource, $fileNameSource, $dirDestination , $fileNameDestination);

        $this->imageResize->resize($dirDestination.'/'.$fileNameDestination);
    }

    public static function getGroups(): array
    {
        return ['step1111'];
    }


}
