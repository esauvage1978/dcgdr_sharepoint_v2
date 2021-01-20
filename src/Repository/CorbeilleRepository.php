<?php

namespace App\Repository;

use App\Entity\Corbeille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Corbeille|null find($id, $lockMode = null, $lockVersion = null)
 * @method Corbeille|null findOneBy(array $criteria, array $orderBy = null)
 * @method Corbeille[]    findAll()
 * @method Corbeille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CorbeilleRepository extends ServiceEntityRepository
{
    const ALIAS = 'c';
    const ALIAS_RUBRIC_WRITERS = 'crw';
    const ALIAS_RUBRIC_READERS = 'crr';
    const ALIAS_UNDERRUBRIC_WRITERS = 'curw';
    const ALIAS_UNDERRUBRIC_READERS = 'curr';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Corbeille::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                UserRepository::ALIAS,
                OrganismeRepository::ALIAS,
                RubricRepository::ALIAS . 'r',
                RubricRepository::ALIAS . 'w'
            )
            ->innerJoin(self::ALIAS . '.organisme', OrganismeRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.users', UserRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.rubricReaders', RubricRepository::ALIAS . 'r')
            ->leftJoin(self::ALIAS . '.rubricWriters', RubricRepository::ALIAS . 'w')
            ->orderBy(self::ALIAS . '.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllForUser(string $userId)
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS)
            ->leftJoin(self::ALIAS . '.users', UserRepository::ALIAS)
            ->where(UserRepository::ALIAS . '.id = :user')
            ->setParameter('user', $userId)
            ->orderBy(self::ALIAS . '.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
