<?php

namespace App\Service;

use App\Dto\BackpackDto;
use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\BackpackDtoRepository;
use App\Widget\WidgetInfoBox;
use App\Workflow\WorkflowData;

class MakeDashboard
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

    private function getState($state,$color)
    {
        $dto = new BackpackDto();
        $ib = new WidgetInfoBox();

        $dto = $this->checkUser($dto);

        $dto->setCurrentState($state);

        return $ib
            ->setRoute('backpacks_show_state')
            ->setRouteOptions(['state' => $state])
            ->setColor($color)
            ->setIcone('fas fa-bezier-curve')
            ->setTitle(WorkflowData::getNameOfState($state))
            ->setData($this->backpackDtoRepository->countForDto($dto))
            ->createArray();

    }
    public function getDraft()
    {
        return $this->getState( WorkflowData::STATE_DRAFT, 'info');
    }

    public function getPublished()
    {
        return $this->getState( WorkflowData::STATE_PUBLISHED, 'success');
    }

    public function getArchived()
    {
        return $this->getState( WorkflowData::STATE_ARCHIVED, 'warning');
    }

    public function getAbandonned()
    {
        return $this->getState( WorkflowData::STATE_ABANDONNED, 'danger');
    }

    public function getHide()
    {
        $dto = new BackpackDto();
        $ib = new WidgetInfoBox();

        $dto = $this->checkUser($dto);
        $dto->setHide(BackpackDto::TRUE);

        return $ib
            ->setRoute('backpacks_show_hide')
            ->setColor('default')
            ->setIcone('fas fa-bezier-curve')
            ->setTitle('caché')
            ->setData($this->backpackDtoRepository->countForDto($dto))
            ->createArray();
    }

    private function checkUser(BackpackDto $dto)
    {
        if (!is_null($this->user) && !$this->gestionnaire) {
            $dto->setUserDto((new UserDto())->setId($this->user->getId()));
        }
        return $dto;

    }

}
