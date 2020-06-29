<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    const ALIAS = 'u';
    const ALIAS_RUBRIC_WRITERS='urw';
    const ALIAS_RUBRIC_READERS='urr';
    const ALIAS_UNDERRUBRIC_WRITERS='uurw';
    const ALIAS_UNDERRUBRIC_READERS='uurr';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                OrganismeRepository::ALIAS,
                CorbeilleRepository::ALIAS
            )
            ->leftJoin(self::ALIAS.'.organismes',OrganismeRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.corbeilles',CorbeilleRepository::ALIAS)
            ->orderBy(self::ALIAS . '.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllUserSubscription()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS
            )
            ->where(self::ALIAS.'.isEnable=true')
            ->andWhere(self::ALIAS.'.subscription=true')
            ->andWhere(self::ALIAS.'.emailValidated=true')
            ->orderBy(self::ALIAS . '.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
