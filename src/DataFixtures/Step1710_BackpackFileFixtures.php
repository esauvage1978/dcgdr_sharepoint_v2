<?php

namespace App\DataFixtures;

use App\Helper\Slugger;
use App\Entity\Backpack;
use App\Helper\FileTools;
use App\Entity\BackpackFile;
use App\Helper\DirectoryTools;
use App\Helper\ParamsInServices;
use App\Helper\FixturesImportData;
use App\Helper\SlugFiles;
use App\Helper\SplitNameOfFile;
use App\Repository\BackpackRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Step1710_BackpackFileFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'mb_pj';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var Backpack[]
     */
    private $backpacks;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * @var FileTools
     */
    private $fileTools;

    /**
     * @var DirectoryTools
     */
    private $DirectoryTools;

    /**
     * @var ParamsInServices
     */
    private $params;

    public function __construct(
        FixturesImportData $fixturesImportData,
        BackpackRepository $backpackRepository,
        EntityManagerInterface $entityManagerI,
        ParamsInServices $params
    )
    {
        $this->fixturesImportData = $fixturesImportData;
        $this->backpacks = $backpackRepository->findAll();
        $this->entityManagerInterface = $entityManagerI;
        $this->params = $params;

        $this->directoryTools = new DirectoryTools();
        $this->fileTools = new FileTools();


    }

    public function getInstance(string $id, $entitys)
    {
        foreach ($entitys as $entity) {
            if ($entity->getId() == $id) {
                return $entity;
            }
        }
        return null;
    }

    public function load(ObjectManager $manager)
    {
        $slugFile = new SlugFiles();
        $slugFile->toSlug($this->params->get(ParamsInServices::DIRECTORY_FIXTURES_DOC));


        $data = $this->fixturesImportData->importToArray(self::FILENAME . '.json');

        for ($i = 0; $i < \count($data); ++$i) {
            $instance = $this->initialise(new BackpackFile(), $data[$i]);

            if (null !== $instance) {
                $this->checkAndPersist($instance);
            }
        }

        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(BackpackFile $instance)
    {
        $this->entityManagerInterface->persist($instance);
    }

    private function initialise(BackpackFile $instance, $data): ?BackpackFile
    {
        if ('document' != $data['domaine'] ||
            '0' == $data['afficher'] ||
            '1' == $data['lien']) {
            return null;
        }

        /** @var Backpack $backpack */
        $backpack = $this->getInstance($data['obj_num'], $this->backpacks);
        $sf=new  SplitNameOfFile($data['adresse']);
        $slugified = Slugger::slugify($sf->getName());
        $filename= $slugified .'.'. $sf->getExtension();
        if (is_a($backpack, Backpack::class) and $data['titre']!='Thumbs'
        ) {
            $instance
                ->setTitle($data['titre'])
                ->setFileExtension($data['extension'])
                ->setSize($this->fileTools->size($this->params->get(ParamsInServices::DIRECTORY_UPLOAD_BACKPACK_DOC),$filename))
                ->setModifyAt
                (
                    $data['date_update'] == "01/01/0001 00:00:00" ?
                        $backpack->getUpdatedAt() :
                        $this->convertDate($data['date_update'])
                )
                ->setFileName($slugified)
                ->setBackpack($backpack)
                ->setContent($data['description'])
            ;

            $this->moveFile($backpack->getId(), $filename);

            return $instance;
        }

        return null;
    }

    private function moveFile(string $backpackId, string $fileName)
    {

        $dirDestination = $this->params->get(ParamsInServices::DIRECTORY_UPLOAD_BACKPACK_DOC);
        $dirSource = $this->params->get(ParamsInServices::DIRECTORY_FIXTURES_DOC);

        if (!$this->directoryTools->exist($dirDestination, $backpackId)) {
            $this->directoryTools->create($dirDestination, $backpackId);
        }

        if(!$this->fileTools->exist($dirDestination . '/' . $backpackId.'/',$fileName) && $this->fileTools->exist($dirSource, $fileName)) {
            $this->fileTools->move($dirSource, $fileName, $dirDestination . '/' . $backpackId, $fileName);
        }
    }

    public static function getGroups(): array
    {
        return ['step1710'];
    }

    public function convertDate(?string $date): ?\DateTimeInterface
    {
        if (null === $date) {
            return null;
        }

        return new \DateTime(str_replace('/', '-', $date));
    }
}
