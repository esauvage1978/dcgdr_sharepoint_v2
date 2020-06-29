<?php

namespace App\Service;

use App\Dto\BackpackDto;
use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\BackpackDtoRepository;
use App\Widget\WidgetInfoBox;
use App\Workflow\WorkflowData;

class BackpackCounter
{

    /**
     * @var BackpackDtoRepository
     */
    private $backpackDtoRepository;

    private $user;

    private $gestionnaire;

    public function __construct(
        BackpackDtoRepository $backpackDtoRepository,
        User $user,
        bool $gestionnaire
    )
    {
        $this->backpackDtoRepository = $backpackDtoRepository;
        $this->user = $user;
        $this->gestionnaire = $gestionnaire;
    }


    public function getDraft():string
    {
        $dto = new BackpackDto();
        $dto = $this->checkUser($dto);
        $dto
            ->setVisible(BackpackDto::TRUE)
            ->setCurrentState(WorkflowData::STATE_DRAFT);
        return $this->backpackDtoRepository->countForDto($dto);
    }
    public function getMyDraft():string
    {
        $dto = new BackpackDto();
        $dto = $this->checkUser($dto);
        if (!is_null($this->user)) {
            $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
        }
        $dto
            ->setVisible(BackpackDto::TRUE)
            ->setCurrentState(WorkflowData::STATE_DRAFT);
        return $this->backpackDtoRepository->countForDto($dto);
    }
    public function getPublished()
    {
        $dto = new BackpackDto();
        $dto = $this->checkUser($dto);
        $dto
            ->setVisible(BackpackDto::TRUE)
            ->setCurrentState(WorkflowData::STATE_PUBLISHED);
        return $this->backpackDtoRepository->countForDto($dto);
    }
    public function getNews()
    {
        $dto = new BackpackDto();
        $dto = $this->checkUser($dto);
        $dto
            ->setIsNew(BackpackDto::TRUE)
            ->setVisible(BackpackDto::TRUE)
            ->setCurrentState(WorkflowData::STATE_PUBLISHED);
        return $this->backpackDtoRepository->countForDto($dto);
    }

    public function getArchived()
    {
        $dto = new BackpackDto();
        $dto = $this->checkUser($dto);
        $dto
            ->setVisible(BackpackDto::TRUE)
            ->setCurrentState(WorkflowData::STATE_ARCHIVED);
        return $this->backpackDtoRepository->countForDto($dto);
    }

    public function getAbandonned()
    {
        $dto = new BackpackDto();
        $dto = $this->checkUser($dto);
        $dto
            ->setVisible(BackpackDto::TRUE)
            ->setCurrentState(WorkflowData::STATE_ABANDONNED);
        return $this->backpackDtoRepository->countForDto($dto);
    }

    public function getHide()
    {
        $dto = new BackpackDto();
        $dto = $this->checkUser($dto);
        $dto
            ->setHide(BackpackDto::TRUE)
            ;
        return $this->backpackDtoRepository->countForDto($dto);
    }

    private function checkUser(BackpackDto $dto)
    {
        if (!is_null($this->user) && !$this->gestionnaire) {
            $dto->setUserDto((new UserDto())->setId($this->user->getId()));
        }
        return $dto;

    }

}
