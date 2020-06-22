<?php

namespace App\Repository;


use App\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Picture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Picture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Picture[]    findAll()
 * @method Picture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PictureRepository extends ServiceEntityRepository
{
    const ALIAS='p';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Picture::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS
                )
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
