<?php

namespace App\DataFixtures;

use App\Entity\Backpack;
use App\Entity\UnderRubric;
use App\Entity\User;
use App\Helper\FixturesImportData;
use App\Repository\UnderRubricRepository;
use App\Repository\UserRepository;
use App\Validator\BackpackValidator;
use App\Workflow\WorkflowData;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class Step1500_BackpackFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'document';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var BackpackValidator
     */
    private $validator;

    /**
     * @var User
     */
    private $user;

    /**
     * @var UnderRubric[]
     */
    private $underrubrics;

    /**
     * @var User[]
     */
    private $users;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        BackpackValidator $validator,
        UnderRubricRepository $underRubricRepository,
        EntityManagerInterface $entityManagerI,
        UserRepository $userRepository
    )
    {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->underrubrics = $underRubricRepository->findAll();
        $this->entityManagerInterface = $entityManagerI;
        $this->user=$userRepository->find(1);
        $this->users = $userRepository->findAll();
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->fixturesImportData->importToArray(self::FILENAME . '.json');
        for ($i = 0; $i < count($data); ++$i) {
            $instance = $this->initialise(new Backpack(), $data[$i]);

            $this->checkAndPersist($instance);

        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist(Backpack $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Backpack::class);
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


    private function initialise(Backpack $instance, $data): Backpack
    {
        /** @var UnderRubric $underrubric */
        $underrubric = $this->getInstance($data['id_sousthematique'], $this->underrubrics);

        $user = $this->getInstance($data['id_u_create'], $this->users);

        $instance
            ->setId($data['n0_num'])
            ->setName($data['nom'])
            ->setContent($data['description'])
            ->setDir1($data['n1'])
            ->setDir2($data['n2'])
            ->setDir3($data['n3'])
            ->setDir4($data['n4'])
            ->setDir5($data['n5'])
            ->setStateCurrent($data['afficher']=='1'?WorkflowData::STATE_PUBLISHED:WorkflowData::STATE_ABANDONNED)
            ->setStateAt($this->convertDate($data['date_create']))
            ->setStateContent('Version 1.0.0')
            ->setOwner($user===null? $this->user:$user)
            ->setUpdatedAt
            (
                $data['date_update'] == "01/01/0001 00:00:00" ?
                    $this->convertDate($data['date_create']) :
                    $this->convertDate($data['date_update'])
            );

        if (!empty($underrubric)) {
            $instance->setUnderRubric($underrubric);
        }


        return $instance;
    }

    public function convertDate(?string $date): ?\DateTimeInterface
    {
        if (null === $date) {
            return null;
        }

        return new DateTime(str_replace('/', '-', $date));
    }

    public static function getGroups(): array
    {
        return ['step1500'];
    }


}
