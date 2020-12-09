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
     * @var BackpackCounter
     */
    private $counter;


    private const ROUTE = 'route';
    private const ROUTE_OPTIONS = 'route_options';
    private const BG_COLOR = 'bgColor';
    private const FORE_COLOR = 'foreColor';
    private const TITLE = 'title';
    private const ICONE = 'icone';
    private const NBR = 'nbr';


    private const STATE = 'state';


    public function getData(string $filter)
    {
        $datas = [
            BackpackMakerDto::DRAFT => [
                self::STATE =>  WorkflowData::STATE_DRAFT,
                self::TITLE => 'Tous les brouillons',
            ],

            BackpackMakerDto::MY_DRAFT_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_DRAFT,
                self::TITLE => 'Mes brouillons',
            ],
            BackpackMakerDto::ABANDONNED => [
                self::STATE =>  WorkflowData::STATE_ABANDONNED,
                self::TITLE => 'Les abandonnés',
            ],

            BackpackMakerDto::MY_ABANDONNED_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_ABANDONNED,
                self::TITLE => 'Mes abandonnés',
            ],
            BackpackMakerDto::PUBLISHED => [
                self::STATE =>  WorkflowData::STATE_PUBLISHED,
                self::TITLE => 'Les publiés',
            ],
 
            BackpackMakerDto::MY_PUBLISHED_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_PUBLISHED,
                self::TITLE => 'Mes publiés modifiables',
            ],
            BackpackMakerDto::ARCHIVED => [
                self::STATE =>  WorkflowData::STATE_ARCHIVED,
                self::TITLE => 'Les archives',
            ],

            BackpackMakerDto::MY_ARCHIVED_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_ARCHIVED,
                self::TITLE => 'Mes archives modifiables',
            ],

        ];


        return $this->getArray($datas[$filter], $filter);
    }

    public function __construct(
        BackpackDtoRepository $backpackDtoRepository,
        User $user
    ) {
        $this->counter = new BackpackCounter($backpackDtoRepository, $user);
    }

    private function getArray($datas, $filter)
    {
        $ib = new WidgetInfoBox();

        return $ib
            ->setRoute('backpacks_' . $filter)
            ->setRouteOptions(key_exists(self::ROUTE_OPTIONS, $datas) ? $datas[self::ROUTE_OPTIONS] : null)
            ->setBgColor(WorkflowData::getBGColorOfState($datas[self::STATE]))
            ->setForeColor(WorkflowData::getForeColorOfState($datas[self::STATE]))
            ->setIcone(WorkflowData::getIconOfState($datas[self::STATE]))
            ->setTitle($datas[self::TITLE])
            ->setData($this->counter->get($filter))
            ->createArray();
    }
}
