<?php


namespace App\Repository;

use App\Dto\DtoInterface;
use App\Dto\UnderRubricDto;
use App\Entity\UnderRubric;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

class UnderRubricDtoRepository extends ServiceEntityRepository implements DtoRepositoryInterface
{
    use TraitDtoRepository;

    /**
     * @var UnderRubricDto
     */
    private $dto;

    const FILTRE_DTO_INIT_HOME = 'home';
    const FILTRE_DTO_INIT_TABLEAU = 'tableau';
    const FILTRE_DTO_INIT_SEARCH = 'search';
    const FILTRE_DTO_INIT_UNITAIRE = 'unitaire';

    const ALIAS = 'ur';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnderRubric::class);
    }

    public function countForDto(DtoInterface $dto)
    {
        /**
         * var ContactDto
         */
        $this->dto = $dto;

        $this->initialise_selectCount();

        $this->initialise_where();

        $this->initialise_orderBy();

        return $this->builder
            ->getQuery()->getSingleScalarResult();
    }

    public function findAllForDtoPaginator(DtoInterface $dto, $page = null, $limit = null)
    {
        /**
         * var ContactDto
         */
        $this->dto = $dto;

        $this->initialise_selectAll();

        $this->initialise_where();

        $this->initialise_orderBy();

        if (empty($page)) {
            $this->builder
                ->getQuery()
                ->getResult();
        } else {
            $this->builder
                ->setFirstResult(($page - 1) * $limit)
                ->setMaxResults($limit);
        }

        return new Paginator($this->builder);
    }

    public function findForCombobox(DtoInterface $dto)
    {
        $this->dto = $dto;

        $this->initialise_selectCombobox();

        $this->initialise_where();

        $this->initialise_orderBy_Combobox();

        return $this->builder
            ->getQuery()
            ->getResult();
    }

    private function initialise_selectCombobox()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('distinct ' . self::ALIAS . '.id, ' . self::ALIAS . '.name')
            ->Join(self::ALIAS . '.underThematic', UnderThematicRepository::ALIAS)
            ->LeftJoin(self::ALIAS . '.rubric', RubricRepository::ALIAS)
            ->LeftJoin(RubricRepository::ALIAS . '.thematic', ThematicRepository::ALIAS);
    }

    public function findAllForDto(DtoInterface $dto, string $filtre = self::FILTRE_DTO_INIT_HOME)
    {
        /**
         * var ContactDto
         */
        $this->dto = $dto;

        switch ($filtre) {
            case self::FILTRE_DTO_INIT_TABLEAU:
                $this->initialise_selectAll();
                break;
            case self::FILTRE_DTO_INIT_UNITAIRE:
                $this->initialise_selectAll();
                break;
            case self::FILTRE_DTO_INIT_HOME:
                $this->initialise_select_home();
                break;
            case self::FILTRE_DTO_INIT_SEARCH:
                $this->initialise_selectAll();
                break;
        }

        $this->initialise_where();

        $this->initialise_orderBy();

        return $this->builder
            ->getQuery()
            ->getResult();

    }

    private function initialise_select_home()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                PictureRepository::ALIAS,
                RubricRepository::ALIAS,
                ThematicRepository::ALIAS,
                UnderThematicRepository::ALIAS
            )
            ->leftJoin(self::ALIAS . '.picture', PictureRepository::ALIAS)
            ->Join(self::ALIAS . '.underThematic', UnderThematicRepository::ALIAS)
            ->Join(self::ALIAS . '.rubric', RubricRepository::ALIAS)
            ->Join(RubricRepository::ALIAS . '.thematic', ThematicRepository::ALIAS);
    }


    private function initialise_selectAll()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                UnderThematicRepository::ALIAS,
                RubricRepository::ALIAS,
                ThematicRepository::ALIAS
            )
            ->leftJoin(self::ALIAS . '.underThematic', UnderThematicRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.rubric', RubricRepository::ALIAS)
            ->leftJoin(RubricRepository::ALIAS . '.thematic', ThematicRepository::ALIAS);


    }

    private function initialise_selectCount()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('count(distinct ' . self::ALIAS . '.id)')
            ->leftJoin(self::ALIAS . '.underThematic', UnderThematicRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.rubric', RubricRepository::ALIAS)
            ->leftJoin(RubricRepository::ALIAS . '.thematic', ThematicRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.writers', CorbeilleRepository::ALIAS_UNDERRUBRIC_WRITERS)
            ->leftJoin(CorbeilleRepository::ALIAS_UNDERRUBRIC_WRITERS . '.users', UserRepository::ALIAS_UNDERRUBRIC_WRITERS)
            ->leftJoin(self::ALIAS . '.readers', CorbeilleRepository::ALIAS_UNDERRUBRIC_READERS)
            ->leftJoin(CorbeilleRepository::ALIAS_UNDERRUBRIC_READERS . '.users', UserRepository::ALIAS_UNDERRUBRIC_READERS)
            ->leftJoin(RubricRepository::ALIAS . '.writers', CorbeilleRepository::ALIAS_RUBRIC_WRITERS)
            ->leftJoin(CorbeilleRepository::ALIAS_RUBRIC_WRITERS . '.users', UserRepository::ALIAS_RUBRIC_WRITERS)
            ->leftJoin(RubricRepository::ALIAS . '.readers', CorbeilleRepository::ALIAS_RUBRIC_READERS)
            ->leftJoin(CorbeilleRepository::ALIAS_RUBRIC_READERS . '.users', UserRepository::ALIAS_RUBRIC_READERS);
    }

    private function initialise_where()
    {
        $this->params = [];
        $dto = $this->dto;

        $this->builder
            ->where(self::ALIAS . '.id>0');

        $this->initialise_where_underThematic();

        $this->initialise_where_rubric();

        $this->initialise_where_enable();

        $this->initialise_where_user_can_show();

        $this->initialise_where_search();

        if (count($this->params) > 0) {
            $this->builder->setParameters($this->params);
        }

    }

    private function initialise_where_user_can_show()
    {
        $u = $this->dto->getUserDto();
        if (!empty($u) && !empty($u->getId())) {

            $qURWC = $this->createQueryBuilder(self::ALIAS . '1')
                ->select(self::ALIAS . '1.id')
                ->Join(self::ALIAS . '1.writers', CorbeilleRepository::ALIAS_UNDERRUBRIC_WRITERS)
                ->Join(CorbeilleRepository::ALIAS_UNDERRUBRIC_WRITERS . '.users', UserRepository::ALIAS_UNDERRUBRIC_WRITERS)
                ->Where(UserRepository::ALIAS_UNDERRUBRIC_WRITERS . '.id= :idUser');

            $qURRC = $this->createQueryBuilder(self::ALIAS . '2')
                ->select(self::ALIAS . '2.id')
                ->Join(self::ALIAS . '2.writers', CorbeilleRepository::ALIAS_UNDERRUBRIC_READERS)
                ->Join(CorbeilleRepository::ALIAS_UNDERRUBRIC_READERS . '.users', UserRepository::ALIAS_UNDERRUBRIC_READERS)
                ->Where(UserRepository::ALIAS_UNDERRUBRIC_READERS . '.id= :idUser');

            $qRWC = $this->createQueryBuilder(self::ALIAS . '3')
                ->select(self::ALIAS . '3.id')
                ->Join(self::ALIAS . '3.rubric', RubricRepository::ALIAS . '1')
                ->Join(RubricRepository::ALIAS . '1.writers', CorbeilleRepository::ALIAS_RUBRIC_WRITERS)
                ->Join(CorbeilleRepository::ALIAS_RUBRIC_WRITERS . '.users', UserRepository::ALIAS_RUBRIC_WRITERS)
                ->Where(UserRepository::ALIAS_RUBRIC_WRITERS . '.id= :idUser');

            $qRRC = $this->createQueryBuilder(self::ALIAS . '4')
                ->select(self::ALIAS . '4.id')
                ->Join(self::ALIAS . '4.rubric', RubricRepository::ALIAS . '2')
                ->Join(RubricRepository::ALIAS . '2.writers', CorbeilleRepository::ALIAS_RUBRIC_READERS)
                ->Join(CorbeilleRepository::ALIAS_RUBRIC_READERS . '.users', UserRepository::ALIAS_RUBRIC_READERS)
                ->Where(UserRepository::ALIAS_RUBRIC_READERS . '.id= :idUser');

            $this->addParams('idUser', $u->getId());


            $this->builder
                ->andWhere(
                    self::ALIAS . '.id IN (' . $qRWC->getDQL() . ')' .
                    ' OR ' . self::ALIAS . '.id IN (' . $qRRC->getDQL() . ')' .
                    ' OR ' . self::ALIAS . '.id IN (' . $qURWC->getDQL() . ')' .
                    ' OR ' . self::ALIAS . '.id IN (' . $qURRC->getDQL() . ')' .
                    ' OR ' . RubricRepository::ALIAS . '.isShowAll = 1' .
                    ' OR ' . self::ALIAS . '.isShowAll = 1');

        }
    }

    private function initialise_where_underThematic()
    {
        $ut = $this->dto->getUnderThematicDto();
        if (!empty($ut) && !empty($ut->getId())) {
            $this->builder->andwhere(UnderThematicRepository::ALIAS . '.id = :underthematicid');
            $this->addParams('underthematicid', $ut->getId());
        }
    }

    private function initialise_where_rubric()
    {
        $r = $this->dto->getRubricDto();
        if (!empty($r) && !empty($r->getId())) {
            $this->builder->andwhere(RubricRepository::ALIAS . '.id = :rubricid');
            $this->addParams('rubricid', $r->getId());
        }
    }

    private function initialise_where_enable()
    {
        $e = $this->dto->getIsEnable();
        if (!empty($e)) {
            if ($e == UnderRubricDto::TRUE) {
                $this->builder->andwhere(self::ALIAS . '.isEnable= true');
            } elseif ($e == UnderRubricDto::FALSE) {
                $this->builder->andwhere(self::ALIAS . '.isEnable= false');
            }
        }
        $e = $this->dto->getThematicDto();
        if (!empty($e) && !empty($e->getIsEnable())) {
            if ($e->getIsEnable() == UnderRubricDto::TRUE) {
                $this->builder->andwhere(ThematicRepository::ALIAS . '.isEnable= true');
            } elseif ($e->getIsEnable() == UnderRubricDto::FALSE) {
                $this->builder->andwhere(ThematicRepository::ALIAS . '.isEnable= false');
            }
        }
        $e = $this->dto->getUnderThematicDto();
        if (!empty($e) && !empty($e->getIsEnable())) {
            if ($e->getIsEnable() == UnderRubricDto::TRUE) {
                $this->builder->andwhere(UnderThematicRepository::ALIAS . '.isEnable= true');
            } elseif ($e->getIsEnable() == UnderRubricDto::FALSE) {
                $this->builder->andwhere(UnderThematicRepository::ALIAS . '.isEnable= false');
            }
        }
        $e = $this->dto->getRubricDto();
        if (!empty($e) && !empty($e->getIsEnable())) {
            if ($e->getIsEnable() == UnderRubricDto::TRUE) {
                $this->builder->andwhere(RubricRepository::ALIAS . '.isEnable= true');
            } elseif ($e->getIsEnable() == UnderRubricDto::FALSE) {
                $this->builder->andwhere(RubricRepository::ALIAS . '.isEnable= false');
            }
        }
    }


    private function initialise_where_search()
    {
        $dto = $this->dto;
        $builder = $this->builder;
        if (!empty($dto->getWordSearch())) {
            $builder
                ->andwhere(
                    self::ALIAS . '.content like :search' .
                    ' OR ' . self::ALIAS . '.name like :search' .
                    ' OR ' . UnderThematicRepository::ALIAS . '.name like :search');

            $this->addParams('search', '%' . $dto->getWordSearch() . '%');
        }

    }

    private function initialise_orderBy()
    {
        $this->builder
            ->orderBy(self::ALIAS . '.showOrder', 'ASC')
            ->addOrderBy(UnderThematicRepository::ALIAS . '.name', 'ASC')
            ->addOrderBy(self::ALIAS . '.name', 'ASC');
    }

    private function initialise_orderBy_Combobox()
    {
        $this->builder
            ->orderBy(self::ALIAS . '.name', 'ASC');
    }

}