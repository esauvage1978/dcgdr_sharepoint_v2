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
    public const DRAFT = 'draft';
    public const MY_DRAFT_UPDATABLE = 'mydraft_updatable';

    public const ABANDONNED = 'abandonned';
    public const MY_ABANDONNED_UPDATABLE = 'myabandonned_updatable';

    public const PUBLISHED = 'published';
    public const MY_PUBLISHED_UPDATABLE = 'mypublished_updatable';

    public const ARCHIVED = 'archived';
    public const MY_ARCHIVED_UPDATABLE = 'myarchived_updatable';

    public const ALL = 'all';

    const SEARCH = 'search';
    const PUBLISHED_FOR_RUBRIC = 'published_for_rubric';
    const PUBLISHED_FOR_UNDERRUBRIC = 'published_for_underrubric';
    const NEWS = 'news';
    const NEWS_FOR_RUBRIC = 'news_for_rubric';
    const NEWS_FOR_UNDERRUBRIC = 'news_for_underrubric';


    
    const HIDE = 'hide';

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
        $this->gestionnaire = Role::isGestionnaire($this->user);
    }


    public function get(string $type, ?string $param = null): BackpackDto
    {
        $dto = new BackpackDto();
        $dto = $this->checkUser($dto);
        switch ($type) {
            case self::ALL:
                $dto
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::DRAFT:
                $dto
                    ->setStateCurrent(WorkflowData::STATE_DRAFT)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::MY_DRAFT_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_DRAFT)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::MY_PUBLISHED_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_PUBLISHED)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::PUBLISHED:
                $dto
                    ->setStateCurrent(WorkflowData::STATE_PUBLISHED)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::MY_ARCHIVED_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_ARCHIVED)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::ARCHIVED:
                $dto
                    ->setStateCurrent(WorkflowData::STATE_ARCHIVED)
                    ->setVisible(BackpackDto::TRUE);
                break;                
            case self::SEARCH:
                if (null === $param) {
                    throw new \InvalidArgumentException('Il manque le critère de recherche');
                }
                $dto
                    ->setWordSearch($param)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::PUBLISHED_FOR_RUBRIC:
                if (null === $param) {
                    throw new \InvalidArgumentException('Il manque l\'id de la rubrique');
                }
                $dto->setRubricDto((new RubricDto())->setId($param));
                $dto
                    ->setStateCurrent(WorkflowData::STATE_PUBLISHED)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::PUBLISHED_FOR_UNDERRUBRIC:
                if (null === $param) {
                    throw new \InvalidArgumentException('Il manque l\'id de la sous rubrique');
                }
                $dto->setUnderRubricDto((new UnderRubricDto())->setId($param));
                $dto
                    ->setStateCurrent(WorkflowData::STATE_PUBLISHED)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::NEWS:
                $dto
                    ->setIsNew(BackpackDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_PUBLISHED)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::NEWS_FOR_RUBRIC:
                if (null === $param) {
                    throw new \InvalidArgumentException('Il manque l\'id de la rubrique');
                }
                $dto->setRubricDto((new RubricDto())->setId($param));
                $dto
                    ->setIsNew(BackpackDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_PUBLISHED)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::NEWS_FOR_UNDERRUBRIC:
                if (null === $param) {
                    throw new \InvalidArgumentException('Il manque l\'id de la sous rubrique');
                }
                $dto->setUnderRubricDto((new UnderRubricDto())->setId($param));
                $dto
                    ->setIsNew(BackpackDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_PUBLISHED)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::ARCHIVED:
                $dto
                    ->setStateCurrent(WorkflowData::STATE_ARCHIVED)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::MY_ABANDONNED_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_ABANDONNED)
                    ->setVisible(BackpackDto::TRUE);
                break;                
            case self::ABANDONNED:
                $dto
                    ->setStateCurrent(WorkflowData::STATE_ABANDONNED)
                    ->setVisible(BackpackDto::TRUE);
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
