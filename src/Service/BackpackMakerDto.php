<?php

namespace App\Service;

use App\Dto\BackpackDto;
use App\Dto\RubricDto;
use App\Dto\UnderRubricDto;
use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\BackpackDtoRepository;
use App\Security\Role;
use App\Workflow\WorkflowData;

class BackpackMakerDto
{
    Const DRAFT='draft';
    Const MY_DRAFT='mydraft';
    Const PUBLISHED='published';
    Const PUBLISHED_FOR_SEARCH='published_for_search';
    Const PUBLISHED_FOR_RUBRIC='published_for_rubric';
    Const PUBLISHED_FOR_UNDERRUBRIC='published_for_underrubric';
    Const NEWS='news';
    Const NEWS_FOR_RUBRIC='news_for_rubric';
    Const NEWS_FOR_UNDERRUBRIC='news_for_underrubric';
    Const ARCHIVED='archived';
    Const ABANDONNED='abandonned';
    Const HIDE='hide';

    /**
     * @var User
     */
    private $user;

    /**
     * @var bool
     */
    private $gestionnaire;

    public function __construct(?User $user)
    {
        $this->user = $user;
        $this->gestionnaire = Role::isGestionnaire( $this->user);
    }


    public function get(string $type,?string $param=null): BackpackDto
    {
        $dto = new BackpackDto();
        $dto = $this->checkUser($dto);
        switch ($type)
        {
            case self::DRAFT:
                $dto
                    ->setVisible(BackpackDto::TRUE)
                    ->setCurrentState(WorkflowData::STATE_DRAFT);
                break;
            case self::MY_DRAFT:
                if (!is_null($this->user)) {
                    $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setVisible(BackpackDto::TRUE)
                    ->setCurrentState(WorkflowData::STATE_DRAFT);
                break;
            case self::PUBLISHED:
                $dto
                    ->setVisible(BackpackDto::TRUE)
                    ->setCurrentState(WorkflowData::STATE_PUBLISHED);
                break;
            case self::PUBLISHED_FOR_SEARCH:
                if(null===$param) {
                    throw new \InvalidArgumentException('Il manque l\'id de la rubrique');
                }
                $dto
                    ->setWordSearch($param)
                    ->setVisible(BackpackDto::TRUE)
                    ->setCurrentState(WorkflowData::STATE_PUBLISHED);
                break;
            case self::PUBLISHED_FOR_RUBRIC:
                if(null===$param) {
                    throw new \InvalidArgumentException('Il manque l\'id de la rubrique');
                }
                $dto->setRubricDto((new RubricDto())->setId($param));
                $dto
                    ->setVisible(BackpackDto::TRUE)
                    ->setCurrentState(WorkflowData::STATE_PUBLISHED);
                break;
            case self::PUBLISHED_FOR_UNDERRUBRIC:
                if(null===$param) {
                    throw new \InvalidArgumentException('Il manque l\'id de la sous rubrique');
                }
                $dto->setUnderRubricDto((new UnderRubricDto())->setId($param));
                $dto
                    ->setVisible(BackpackDto::TRUE)
                    ->setCurrentState(WorkflowData::STATE_PUBLISHED);
                break;
            case self::NEWS:
                $dto
                    ->setIsNew(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE)
                    ->setCurrentState(WorkflowData::STATE_PUBLISHED);
                break;
            case self::NEWS_FOR_RUBRIC:
                if(null===$param) {
                    throw new \InvalidArgumentException('Il manque l\'id de la rubrique');
                }
                $dto->setRubricDto((new RubricDto())->setId($param));
                $dto
                    ->setIsNew(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE)
                    ->setCurrentState(WorkflowData::STATE_PUBLISHED);
                break;
            case self::NEWS_FOR_UNDERRUBRIC:
                if(null===$param) {
                    throw new \InvalidArgumentException('Il manque l\'id de la sous rubrique');
                }
                $dto->setUnderRubricDto((new UnderRubricDto())->setId($param));
                $dto
                    ->setIsNew(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE)
                    ->setCurrentState(WorkflowData::STATE_PUBLISHED);
                break;
            case self::ARCHIVED:
                $dto
                    ->setVisible(BackpackDto::TRUE)
                    ->setCurrentState(WorkflowData::STATE_ARCHIVED);
                break;
            case self::ABANDONNED:
                $dto
                    ->setVisible(BackpackDto::TRUE)
                    ->setCurrentState(WorkflowData::STATE_ABANDONNED);
                break;
            case self::HIDE:
                $dto
                    ->setHide(BackpackDto::TRUE);
                break;
        }

        return $dto;
    }


    private function checkUser(BackpackDto $dto)
    {
        if (!is_null($this->user) && !$this->gestionnaire) {
            $dto->setUserDto((new UserDto())->setId($this->user->getId()));
        }
        return $dto;
    }

}
