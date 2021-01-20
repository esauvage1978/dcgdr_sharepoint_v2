<?php

namespace App\Repository;


use App\Entity\Backpack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Backpack|null find($id, $lockMode = null, $lockVersion = null)
 * @method Backpack|null findOneBy(array $criteria, array $orderBy = null)
 * @method Backpack[]    findAll()
 * @method Backpack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BackpackRepository extends ServiceEntityRepository
{
    const ALIAS='b';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Backpack::class);
    }

    public function findAllFillComboboxDir1(string $id)
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select('distinct '.self::ALIAS.'.dir1 as id ,'. self::ALIAS.'.dir1 as name');

            $builder = $builder
                ->Where(self::ALIAS.'.underRubric = :val1')
                ->andWhere(self::ALIAS.'.dir1 != :dir')
                ->setParameters(['val1'=> $id,'dir'=> ""])
                ->orderBy(self::ALIAS.'.dir1', 'ASC');

        return $builder
            ->getQuery()
            ->getResult();
    }
    public function findAllFillComboboxDir2(string $id, string $data)
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select('distinct '.self::ALIAS.'.dir2 as id ,'. self::ALIAS.'.dir2 as name');

        $builder = $builder
            ->Where(self::ALIAS.'.underRubric = :val1')
            ->andWhere(self::ALIAS.'.dir1 = :dir1')
            ->andWhere(self::ALIAS.'.dir2 != :dir2')
            ->setParameters(['val1'=> $id,'dir1'=> $data, 'dir2'=>""])
            ->orderBy(self::ALIAS.'.dir2', 'ASC');

        return $builder
            ->getQuery()
            ->getResult();
    }
    public function findAllFillComboboxDir3(string $id, string $data)
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select('distinct '.self::ALIAS.'.dir3 as id ,'. self::ALIAS.'.dir3 as name');

        $builder = $builder
            ->Where(self::ALIAS.'.underRubric = :val1')
            ->andWhere(self::ALIAS.'.dir2 = :dir2')
            ->andWhere(self::ALIAS.'.dir3 != :dir3')
            ->setParameters(['val1'=> $id,'dir2'=> $data, 'dir3'=>""])
            ->orderBy(self::ALIAS.'.dir3', 'ASC');

        return $builder
            ->getQuery()
            ->getResult();
    }
    public function findAllFillComboboxDir4(string $id, string $data)
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select('distinct '.self::ALIAS.'.dir4 as id ,'. self::ALIAS.'.dir4 as name');

        $builder = $builder
            ->Where(self::ALIAS.'.underRubric = :val1')
            ->andWhere(self::ALIAS.'.dir3 = :dir3')
            ->andWhere(self::ALIAS.'.dir4 != :dir4')
            ->setParameters(['val1'=> $id,'dir3'=> $data, 'dir4'=>""])
            ->orderBy(self::ALIAS.'.dir4', 'ASC');

        return $builder
            ->getQuery()
            ->getResult();
    }
    public function findAllFillComboboxDir5(string $id, string $data)
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select('distinct '.self::ALIAS.'.dir5 as id ,'. self::ALIAS.'.dir5 as name');

        $builder = $builder
            ->Where(self::ALIAS.'.underRubric = :val1')
            ->andWhere(self::ALIAS.'.dir4 = :dir4')
            ->andWhere(self::ALIAS.'.dir5 != :dir5')
            ->setParameters(['val1'=> $id,'dir4'=> $data, 'dir5'=>""])
            ->orderBy(self::ALIAS.'.dir5', 'ASC');

        return $builder
            ->getQuery()
            ->getResult();
    }

}
