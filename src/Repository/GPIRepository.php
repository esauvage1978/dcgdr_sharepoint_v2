<?php

namespace App\Repository;

use App\Entity\GPI;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GPI|null find($id, $lockMode = null, $lockVersion = null)
 * @method GPI|null findOneBy(array $criteria, array $orderBy = null)
 * @method GPI[]    findAll()
 * @method GPI[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GPIRepository extends ServiceEntityRepository
{
    const ALIAS='i';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GPI::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select( self::ALIAS,
                UserRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.user',UserRepository::ALIAS)
            ->addOrderBy(self::ALIAS. '.page','ASC')
            ->getQuery()
            ->getResult()
            ;
    }


    public function findAllGpi(string $page)
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS)
            ->leftJoin(self::ALIAS . '.user' , UserRepository::ALIAS )
            ->where(self::ALIAS . '.page = :page')
            ->andWhere(self::ALIAS . '.isEnable = 1')
            ->setParameter('page', $page)
            ->orderBy(self::ALIAS . '.modifyAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
