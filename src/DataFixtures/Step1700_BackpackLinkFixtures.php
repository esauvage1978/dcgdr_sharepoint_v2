<?php

namespace App\DataFixtures;

use App\Entity\Backpack;
use App\Entity\BackpackLink;
use App\Helper\FixturesImportData;
use App\Repository\BackpackRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class Step1700_BackpackLinkFixtures extends Fixture implements FixtureGroupInterface
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

    public function __construct(
        FixturesImportData $fixturesImportData,
        BackpackRepository $backpackRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->backpacks = $backpackRepository->findAll();
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
            $instance = $this->initialise(new BackpackLink(), $data[$i]);

            if (null !== $instance) {
                $this->checkAndPersist($instance);
            }
        }

        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(BackpackLink $instance)
    {
        $this->entityManagerInterface->persist($instance);
    }

    private function initialise(BackpackLink $instance, $data): ?BackpackLink
    {
        if ('document' != $data['domaine'] ||
            '0' == $data['afficher'] ||
            '0' == $data['lien']) {
            return null;
        }

        $backpack = $this->getInstance($data['obj_num'], $this->backpacks);

        if (is_a($backpack, Backpack::class)
        ) {
            $instance
                ->setTitle($data['titre'])
                ->setLink($data['adresse'])
                ->setContent($data['description'])
                ->setModifyAt
                (
                    $data['date_update'] == "01/01/0001 00:00:00" ?
                        $backpack->getUpdatedAt() :
                        $this->convertDate($data['date_update'])
                )
                ->setBackpack($backpack)
            ;

            return $instance;
        }

        return null;
    }

    public static function getGroups(): array
    {
        return ['step1700'];
    }
    public function convertDate(?string $date): ?\DateTimeInterface
    {
        if (null === $date) {
            return null;
        }

        return new \DateTime(str_replace('/', '-', $date));
    }
}
