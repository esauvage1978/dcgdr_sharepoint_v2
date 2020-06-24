<?php

namespace App\Repository;


use App\Entity\UnderThematic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UnderThematic|null find($id, $lockMode = null, $lockVersion = null)
 * @method UnderThematic|null findOneBy(array $criteria, array $orderBy = null)
 * @method UnderThematic[]    findAll()
 * @method UnderThematic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnderThematicRepository extends ServiceEntityRepository
{
    const ALIAS='ut';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnderThematic::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS,
                UnderRubricRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.underrubrics',UnderRubricRepository::ALIAS)
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
