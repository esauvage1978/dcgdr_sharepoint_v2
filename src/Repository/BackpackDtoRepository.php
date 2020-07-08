<?php


namespace App\Repository;


use App\Dto\BackpackDto;
use App\Dto\DtoInterface;
use App\Dto\RubricDto;
use App\Entity\Backpack;
use App\Entity\BackpackLink;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BackpackDtoRepository extends ServiceEntityRepository implements DtoRepositoryInterface
{
    use TraitDtoRepository;

    /**
     * @var BackpackDto
     */
    private $dto;

    const ALIAS = 'b';

    const FILTRE_DTO_INIT_HOME = 'home';
    const FILTRE_DTO_INIT_TREE = 'tree';
    const FILTRE_DTO_INIT_SEARCH = 'search';
    const FILTRE_DTO_INIT_UNITAIRE = 'unitaire';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Backpack::class);
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

    public function findAllForDtoPaginator(DtoInterface $dto, $page = null, $limit = null, $select = self::SELECT_ALL)
    {
        /**
         * var ContactDto
         */
        $this->dto = $dto;

        $this->initialise_select();

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

    public function findAllForDto(DtoInterface $dto,string $filtre=self::FILTRE_DTO_INIT_HOME)
    {
        /**
         * var ContactDto
         */
        $this->dto = $dto;

        switch ($filtre) {
            case self::FILTRE_DTO_INIT_TREE:
                $this->initialise_select_tree();
                break;
            case self::FILTRE_DTO_INIT_UNITAIRE:
                $this->initialise_select();
                break;
            case self::FILTRE_DTO_INIT_HOME:
                $this->initialise_select_home();
                break;
            case self::FILTRE_DTO_INIT_SEARCH:
                $this->initialise_select();
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
                RubricRepository::ALIAS,
                ThematicRepository::ALIAS,
                UnderThematicRepository::ALIAS,
                UnderRubricRepository::ALIAS
            )
            ->join(self::ALIAS . '.underRubric', UnderRubricRepository::ALIAS)
            ->join(UnderRubricRepository::ALIAS . '.underThematic', UnderThematicRepository::ALIAS)
            ->join(UnderRubricRepository::ALIAS . '.rubric', RubricRepository::ALIAS)
            ->join(RubricRepository::ALIAS . '.thematic', ThematicRepository::ALIAS)
        ;
    }

    private function initialise_select_tree()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                RubricRepository::ALIAS,
                ThematicRepository::ALIAS,
                UnderThematicRepository::ALIAS,
                UnderRubricRepository::ALIAS,
                BackpackFileRepository::ALIAS,
                BackpackLinkRepository::ALIAS
            )
            ->join(self::ALIAS . '.underRubric', UnderRubricRepository::ALIAS)
            ->join(UnderRubricRepository::ALIAS . '.underThematic', UnderThematicRepository::ALIAS)
            ->join(UnderRubricRepository::ALIAS . '.rubric', RubricRepository::ALIAS)
            ->join(RubricRepository::ALIAS . '.thematic', ThematicRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.backpackFiles',BackpackFileRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.backpackLinks',BackpackLinkRepository::ALIAS)
        ;
    }

    private function initialise_select()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                UnderRubricRepository::ALIAS,
                UnderThematicRepository::ALIAS,
                RubricRepository::ALIAS,
                ThematicRepository::ALIAS
            )
            ->join(self::ALIAS . '.underRubric', UnderRubricRepository::ALIAS)
            ->join(UnderRubricRepository::ALIAS . '.underThematic', UnderThematicRepository::ALIAS)
            ->join(UnderRubricRepository::ALIAS . '.rubric', RubricRepository::ALIAS)
            ->join(RubricRepository::ALIAS . '.thematic', ThematicRepository::ALIAS)
;


    }

    private function initialise_selectCount()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('count(distinct ' . self::ALIAS . '.id)')
            ->join(self::ALIAS . '.underRubric', UnderRubricRepository::ALIAS)
            ->join(UnderRubricRepository::ALIAS . '.underThematic', UnderThematicRepository::ALIAS)
            ->join(UnderRubricRepository::ALIAS . '.rubric', RubricRepository::ALIAS)
            ->join(RubricRepository::ALIAS . '.thematic', ThematicRepository::ALIAS)
            ;
    }

    private function initialise_where()
    {
        $this->params = [];
        $dto = $this->dto;

        $this->builder
            ->where(self::ALIAS . '.id>0');

        $this->initialise_where_user_can_show();

        $this->initialise_where_underRubric();

        $this->initialise_where_rubric();

        $this->initialise_where_owner();

        $this->initialise_where_new();

        $this->initialise_where_enable();

        $this->initialise_where_state();

        $this->initialise_where_search();

        if (count($this->params) > 0) {
            $this->builder->setParameters($this->params);
        }

    }

    private function initialise_where_user_can_show()
    {
        $u=$this->dto->getUserDto();
        if (!empty($u) && !empty($u->getId())) {

            $qWC = $this->createQueryBuilder(self::ALIAS.'1')
                ->select(self::ALIAS.'1.id')
                ->join( self::ALIAS. '1.underRubric', UnderRubricRepository::ALIAS.'1')
                ->join( UnderRubricRepository::ALIAS. '1.writers', CorbeilleRepository::ALIAS_UNDERRUBRIC_WRITERS)
                ->join(CorbeilleRepository::ALIAS_UNDERRUBRIC_WRITERS.'.users', UserRepository::ALIAS_UNDERRUBRIC_WRITERS)
                ->where(UserRepository::ALIAS_UNDERRUBRIC_WRITERS.'.id= :idUser');

            $qRC = $this->createQueryBuilder(self::ALIAS.'2')
                ->select(self::ALIAS.'2.id')
                ->join( self::ALIAS. '2.underRubric', UnderRubricRepository::ALIAS.'2')
                ->join( UnderRubricRepository::ALIAS. '2.writers', CorbeilleRepository::ALIAS_UNDERRUBRIC_READERS)
                ->join(CorbeilleRepository::ALIAS_UNDERRUBRIC_READERS.'.users', UserRepository::ALIAS_UNDERRUBRIC_READERS)
                ->where(UserRepository::ALIAS_UNDERRUBRIC_READERS.'.id= :idUser');

            $qRWC = $this->createQueryBuilder(self::ALIAS.'3')
                ->join( self::ALIAS. '3.underRubric', UnderRubricRepository::ALIAS.'3')
                ->join( UnderRubricRepository::ALIAS. '3.rubric', RubricRepository::ALIAS.'3')
                ->join( RubricRepository::ALIAS. '3.writers', CorbeilleRepository::ALIAS_RUBRIC_WRITERS)
                ->join(CorbeilleRepository::ALIAS_RUBRIC_WRITERS.'.users', UserRepository::ALIAS_RUBRIC_WRITERS)
                ->where(UserRepository::ALIAS_RUBRIC_WRITERS.'.id= :idUser');

            $qRRC = $this->createQueryBuilder(self::ALIAS.'4')
                ->join( self::ALIAS. '4.underRubric', UnderRubricRepository::ALIAS.'4')
                ->join( UnderRubricRepository::ALIAS. '4.rubric', RubricRepository::ALIAS.'4')
                ->join( RubricRepository::ALIAS. '4.writers', CorbeilleRepository::ALIAS_RUBRIC_READERS)
                ->join(CorbeilleRepository::ALIAS_RUBRIC_READERS.'.users', UserRepository::ALIAS_RUBRIC_READERS)
                ->where(UserRepository::ALIAS_RUBRIC_READERS.'.id= :idUser');

            $this->addParams('idUser',$u->getId() );


            $this->builder
                ->andWhere(
                    self::ALIAS. '.id IN (' . $qRWC->getDQL() . ')' .
                    ' OR ' . self::ALIAS. '.id IN (' . $qRRC->getDQL() . ')' .
                    ' OR ' . self::ALIAS. '.id IN (' . $qWC->getDQL() . ')' .
                    ' OR ' . self::ALIAS. '.id IN (' . $qRC->getDQL() . ')' .
                    ' OR ' . RubricRepository::ALIAS . '.isShowAll = 1' .
                    ' OR ' . UnderRubricRepository::ALIAS . '.isShowAll = 1');

        }
    }

    private function initialise_where_new()
    {
        if ($this->dto->getIsNew()==BackpackDto::TRUE ) {
            $to = date('Y-m-d', strtotime((new DateTime())->format('Y-m-d') . ' +1 day'));
            $from = date('Y-m-d', strtotime((new DateTime())->format('Y-m-d') . ' -8 day'));

            $this->builder->andWhere(
                self::ALIAS . '.updateAt BETWEEN  :from AND :to');

            $this->addParams('from', $from);
            $this->addParams('to', $to);
        }
    }

    private function initialise_where_state()
    {

        if (!empty($this->dto->getCurrentState())) {
            $this->builder->andwhere(self::ALIAS . '.currentState = :state');
            $this->addParams('state', $this->dto->getCurrentState());
        }


    }

    private function initialise_where_enable()
    {

        if (!empty($this->dto->getVisible())) {
            $this->builder->andwhere(RubricRepository::ALIAS . '.isEnable= true');
            $this->builder->andWhere(ThematicRepository::ALIAS . '.isEnable= true');
            $this->builder->andWhere(UnderThematicRepository::ALIAS . '.isEnable= true');
            $this->builder->andWhere(UnderRubricRepository::ALIAS . '.isEnable= true');
        }
        else if (!empty($this->dto->getHide()) ) {
            $this->builder->andWhere(
                RubricRepository::ALIAS . '.isEnable= false OR '.
                ThematicRepository::ALIAS . '.isEnable= false OR '.
                UnderThematicRepository::ALIAS . '.isEnable= false OR '.
                UnderRubricRepository::ALIAS . '.isEnable= false ');
        } else {
            $e=$this->dto->getRubricDto();
            if (!empty($e)) {
                if ($e->getIsEnable() == RubricDto::TRUE) {
                    $this->builder->andwhere(RubricRepository::ALIAS . '.isEnable= true');
                } elseif ($e == RubricDto::FALSE) {
                    $this->builder->andwhere(RubricRepository::ALIAS . '.isEnable= false');
                }
            }

            $e=$this->dto->getThematicDto();
            if (!empty($e)) {
                if ($e->getIsEnable() == RubricDto::TRUE) {
                    $this->builder->andWhere(ThematicRepository::ALIAS . '.isEnable= true');
                } elseif ($e->getIsEnable() == RubricDto::FALSE) {
                    $this->builder->andWhere(ThematicRepository::ALIAS . '.isEnable= false');
                }
            }

            $e=$this->dto->getUnderThematicDto();
            if (!empty($e)) {
                if ($e->getIsEnable() == RubricDto::TRUE) {
                    $this->builder->andWhere(UnderThematicRepository::ALIAS . '.isEnable= true');
                } elseif ($e->getIsEnable() == RubricDto::FALSE) {
                    $this->builder->andWhere(UnderThematicRepository::ALIAS . '.isEnable= false');
                }
            }

            $e=$this->dto->getUnderRubricDto();
            if (!empty($e) ) {
                if ($e->getIsEnable() == RubricDto::TRUE) {
                    $this->builder->andWhere(UnderRubricRepository::ALIAS . '.isEnable= true');
                } elseif ($e->getIsEnable() == RubricDto::FALSE) {
                    $this->builder->andWhere(UnderRubricRepository::ALIAS . '.isEnable= false');
                }
            }
        }
    }

    private function initialise_where_underRubric()
    {
        $r = $this->dto->getUnderRubricDto();
        if (!empty($r) && !empty($r->getId())) {
            $this->builder->andwhere(UnderRubricRepository::ALIAS . '.id = :underrubricid');
            $this->addParams('underrubricid', $r->getId());
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

    private function initialise_where_owner()
    {
        $r = $this->dto->getOwnerDto();
        if (!empty($r) && !empty($r->getId())) {
            $this->builder->andwhere(self::ALIAS . '.owner = :ownerid');
            $this->addParams('ownerid', $r->getId());
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
                    ' OR ' . self::ALIAS . '.dir1 like :search' .
                    ' OR ' . self::ALIAS . '.dir2 like :search' .
                    ' OR ' . self::ALIAS . '.dir3 like :search' .
                    ' OR ' . self::ALIAS . '.dir4 like :search' .
                    ' OR ' . self::ALIAS . '.dir5 like :search' .
                    ' OR ' . self::ALIAS . '.name like :search' .
                    ' OR ' . self::ALIAS . '.contentState like :search' .
                    ' OR ' . BackpackLinkRepository::ALIAS . '.title like :search' .
                    ' OR ' . BackpackLinkRepository::ALIAS . '.link like :search' .
                    ' OR ' . BackpackLinkRepository::ALIAS . '.content like :search' .
                    ' OR ' . BackpackFileRepository::ALIAS . '.title like :search' .
                    ' OR ' . BackpackFileRepository::ALIAS . '.fileName like :search' .
                    ' OR ' . BackpackFileRepository::ALIAS . '.content like :search'
);

            $this->addParams('search', '%' . $dto->getWordSearch() . '%');
        }

    }

    private function initialise_orderBy()
    {
        $this->builder
            ->addOrderBy(ThematicRepository::ALIAS . '.name', 'ASC')
            ->addOrderBy(RubricRepository::ALIAS . '.name', 'ASC')
            ->addOrderBy(UnderThematicRepository::ALIAS . '.name', 'ASC')
            ->addOrderBy(UnderRubricRepository::ALIAS . '.name', 'ASC')
            ->addOrderBy(self::ALIAS . '.dir1', 'ASC')
            ->addOrderBy(self::ALIAS . '.dir2', 'ASC')
            ->addOrderBy(self::ALIAS . '.dir3', 'ASC')
            ->addOrderBy(self::ALIAS . '.dir4', 'ASC')
            ->addOrderBy(self::ALIAS . '.dir5', 'ASC')
            ->addOrderBy(self::ALIAS . '.name', 'ASC');
    }


}