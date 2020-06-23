<?php


namespace App\Repository;


use App\Dto\DtoInterface;
use App\Dto\RubricDto;
use App\Entity\Rubric;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

class RubricDtoRepository extends ServiceEntityRepository implements DtoRepositoryInterface
{
    use TraitDtoRepository;

    /**
     * @var RubricDto
     */
    private $dto;

    const FILTRE_DTO_INIT_HOME = 'home';
    const FILTRE_DTO_INIT_TABLEAU = 'tableau';
    const FILTRE_DTO_INIT_SEARCH = 'search';
    const FILTRE_DTO_INIT_UNITAIRE = 'unitaire';

    const ALIAS = 'r';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rubric::class);
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

    public function findForCombobox(DtoInterface $dto)
    {
        $this->dto = $dto;

        $this->initialise_selectCombobox();

        $this->initialise_where();

        $this->initialise_orderBy();

        return $this->builder
            ->getQuery()
            ->getResult();
    }

    public function findAllForDtoPaginator(DtoInterface $dto, $page = null, $limit = null)
    {
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
                ThematicRepository::ALIAS
            )
            ->leftJoin(self::ALIAS . '.picture', PictureRepository::ALIAS)
            ->Join(self::ALIAS . '.thematic', ThematicRepository::ALIAS);
    }

    private function initialise_selectAll()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                'distinct ' . self::ALIAS,
                PictureRepository::ALIAS,
                ThematicRepository::ALIAS,
                UnderRubricRepository::ALIAS,
                UnderThematicRepository::ALIAS,
                BackpackRepository::ALIAS

            )
            ->leftJoin(self::ALIAS . '.picture', PictureRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.thematic', ThematicRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.underRubrics', UnderRubricRepository::ALIAS)
            ->leftJoin(UnderRubricRepository::ALIAS . '.underThematic', UnderThematicRepository::ALIAS)
            ->leftJoin(UnderRubricRepository::ALIAS . '.backpacks', BackpackRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.writers', CorbeilleRepository::ALIAS_RUBRIC_WRITERS)
            ->leftJoin(CorbeilleRepository::ALIAS_RUBRIC_WRITERS . '.users', UserRepository::ALIAS_RUBRIC_WRITERS)
            ->leftJoin(self::ALIAS . '.readers', CorbeilleRepository::ALIAS_RUBRIC_READERS)
            ->leftJoin(CorbeilleRepository::ALIAS_RUBRIC_READERS . '.users', UserRepository::ALIAS_RUBRIC_READERS)
            ->leftJoin(UnderRubricRepository::ALIAS . '.writers', CorbeilleRepository::ALIAS_UNDERRUBRIC_WRITERS)
            ->leftJoin(CorbeilleRepository::ALIAS_UNDERRUBRIC_WRITERS . '.users', UserRepository::ALIAS_UNDERRUBRIC_WRITERS)
            ->leftJoin(UnderRubricRepository::ALIAS . '.readers', CorbeilleRepository::ALIAS_UNDERRUBRIC_READERS)
            ->leftJoin(CorbeilleRepository::ALIAS_UNDERRUBRIC_READERS . '.users', UserRepository::ALIAS_UNDERRUBRIC_READERS);


    }

    private function initialise_selectCombobox()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('distinct '. self::ALIAS.'.id, '.self::ALIAS.'.name')
            ->Join(self::ALIAS . '.thematic', ThematicRepository::ALIAS)
            ->LeftJoin(self::ALIAS . '.underRubrics', UnderRubricRepository::ALIAS)
            ->LeftJoin(UnderRubricRepository::ALIAS . '.underThematic', UnderThematicRepository::ALIAS);
    }

    private function initialise_selectCount()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('count(distinct ' . self::ALIAS . '.id)')
            ->Join(self::ALIAS . '.thematic', ThematicRepository::ALIAS)
            ->Join(self::ALIAS . '.underRubrics', UnderRubricRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.writers', CorbeilleRepository::ALIAS_RUBRIC_WRITERS)
            ->leftJoin(CorbeilleRepository::ALIAS_RUBRIC_WRITERS . '.users', UserRepository::ALIAS_RUBRIC_WRITERS)
            ->leftJoin(self::ALIAS . '.readers', CorbeilleRepository::ALIAS_RUBRIC_READERS)
            ->leftJoin(CorbeilleRepository::ALIAS_RUBRIC_READERS . '.users', UserRepository::ALIAS_RUBRIC_READERS)
            ->leftJoin(UnderRubricRepository::ALIAS . '.writers', CorbeilleRepository::ALIAS_UNDERRUBRIC_WRITERS)
            ->leftJoin(CorbeilleRepository::ALIAS_UNDERRUBRIC_WRITERS . '.users', UserRepository::ALIAS_UNDERRUBRIC_WRITERS)
            ->leftJoin(UnderRubricRepository::ALIAS . '.readers', CorbeilleRepository::ALIAS_UNDERRUBRIC_READERS)
            ->leftJoin(CorbeilleRepository::ALIAS_UNDERRUBRIC_READERS . '.users', UserRepository::ALIAS_UNDERRUBRIC_READERS);
    }

    private function initialise_where()
    {
        $this->params = [];
        $dto = $this->dto;
        $this->builder
            ->where(self::ALIAS . '.id>0');

        $this->initialise_where_thematic();

        $this->initialise_where_underRubric();

        $this->initialise_where_enable();

        $this->initialise_where_user_can_show();


        $this->initialise_where_search();

        if (count($this->params) > 0) {
            $this->builder->setParameters($this->params);
        }

    }


    private function initialise_where_thematic()
    {
        $id=$this->dto->thematicDto->getId();
        if (!empty($id)) {
            $this->builder->andWhere(ThematicRepository::ALIAS . '.id = :thematicid');
            $this->addParams('thematicid', $id);
        }
    }


    private function initialise_where_enable()
    {
        $e=$this->dto->getIsEnable();
        if (!empty($e)) {
            if ($e == RubricDto::TRUE) {

                $this->builder->andWhere(self::ALIAS . '.isEnable= true');
            } elseif ($e == RubricDto::FALSE) {
                $this->builder->andWhere(self::ALIAS . '.isEnable= false');
            }
        }

        $e=$this->dto->thematicDto->getIsEnable();
        if (!empty($e)) {
            if ($e == RubricDto::TRUE) {
                $this->builder->andWhere(ThematicRepository::ALIAS . '.isEnable= true');
            } elseif ($e == RubricDto::FALSE) {
                $this->builder->andWhere(ThematicRepository::ALIAS . '.isEnable= false');
            }
        }

    }

    private function initialise_where_underRubric()
    {
    }

    private function initialise_where_search()
    {
        $dto = $this->dto;
        $builder = $this->builder;
        if (!empty($dto->getWordSearch())) {
            $builder
                ->andWhere(
                    self::ALIAS . '.content like :search' .
                    ' OR ' . self::ALIAS . '.name like :search' .
                    ' OR ' . ThematicRepository::ALIAS . '.name like :search');

            $this->addParams('search', '%' . $dto->getWordSearch() . '%');
        }

    }

    private function initialise_where_user_can_show()
    {
        $u=$this->dto->userDto->getId();
        if (!empty($u)) {

            $qRWC = $this->createQueryBuilder(self::ALIAS . '1')
                ->select(self::ALIAS . '1.id')
                ->Join(self::ALIAS . '1.writers', CorbeilleRepository::ALIAS_RUBRIC_WRITERS)
                ->Join(CorbeilleRepository::ALIAS_RUBRIC_WRITERS . '.users', UserRepository::ALIAS_RUBRIC_WRITERS)
                ->Where(UserRepository::ALIAS_RUBRIC_WRITERS . '.id= :idUser');

            $qRRC = $this->createQueryBuilder(self::ALIAS . '2')
                ->select(self::ALIAS . '2.id')
                ->Join(self::ALIAS . '2.writers', CorbeilleRepository::ALIAS_RUBRIC_READERS)
                ->Join(CorbeilleRepository::ALIAS_RUBRIC_READERS . '.users', UserRepository::ALIAS_RUBRIC_READERS)
                ->Where(UserRepository::ALIAS_RUBRIC_READERS . '.id= :idUser');


            $this->addParams('idUser', $u);


            $this->builder
                ->andWhere(
                    self::ALIAS . '.id IN (' . $qRWC->getDQL() . ')' .
                    ' OR ' . self::ALIAS . '.id IN (' . $qRRC->getDQL() . ')' .
                    ' OR ' . self::ALIAS . '.showAll = 1');

        }
    }


    private function initialise_orderBy()
    {
        $this->builder
            ->orderBy(ThematicRepository::ALIAS . '.showOrder', 'ASC')
            ->addOrderBy(ThematicRepository::ALIAS . '.name', 'ASC')
            ->addOrderBy(self::ALIAS . '.showOrder', 'ASC')
            ->addOrderBy(self::ALIAS . '.name', 'ASC');
    }


}