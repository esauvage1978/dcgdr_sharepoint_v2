<?php

namespace App\DataFixtures;

use App\Entity\Backpack;
use App\Entity\BackpackFile;
use App\Helper\FileDirectory;
use App\Helper\FixturesImportData;
use App\Repository\BackpackRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
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
     * @var FileDirectory
     */
    private $fileDirectory;

    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(
        FixturesImportData $fixturesImportData,
        BackpackRepository $backpackRepository,
        EntityManagerInterface $entityManagerI,
        ParameterBagInterface $params
    )
    {
        $this->fixturesImportData = $fixturesImportData;
        $this->backpacks = $backpackRepository->findAll();
        $this->entityManagerInterface = $entityManagerI;
        $this->fileDirectory = new FileDirectory();
        $this->params = $params;
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

        if (is_a($backpack, Backpack::class) and $data['titre']!='Thumbs'
        ) {
            $instance
                ->setTitle($data['titre'])
                ->setFileExtension($data['extension'])
                ->setSize($this->fileDirectory->fileSize($this->params->get('directory_data_doc'), $data['adresse']))
                ->setModifyAt
                (
                    $data['date_update'] == "01/01/0001 00:00:00" ?
                        $backpack->getUpdateAt() :
                        $this->convertDate($data['date_update'])
                )
                ->setFileName(substr($data['adresse'], 0, strlen($data['adresse']) - 1 - strlen($data['extension'])))
                ->setBackpack($backpack)
                ->setContent($data['description'])
            ;

            $this->moveFile($backpack->getId(), $data['adresse']);

            return $instance;
        }

        return null;
    }

    private function moveFile(string $backpackId, string $fileName)
    {
        $dirDestination = $this->params->get('directory_file_backpack');
        $dirSource = $this->params->get('directory_data_doc');

        $this->fileDirectory->createDir($dirDestination, $backpackId);

        $this->fileDirectory->moveFile($dirSource, $fileName, $dirDestination . '/' . $backpackId, $fileName);
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
