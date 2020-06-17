<?php

namespace App\Repository;

use App\Entity\Organisme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Organisme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Organisme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Organisme[]    findAll()
 * @method Organisme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganismeRepository extends ServiceEntityRepository
{
    const ALIAS='o';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Organisme::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select( self::ALIAS)
            ->orderBy(self::ALIAS . '.ref', 'ASC')
            ->addOrderBy(self::ALIAS. '.name','ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Organisme[] Returns an array of Organisme objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Organisme
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
