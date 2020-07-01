<?php

namespace App\Service;

use App\Dto\BackpackDto;
use App\Dto\RubricDto;
use App\Dto\UnderRubricDto;
use App\Dto\UserDto;
use App\Entity\Backpack;
use App\Entity\User;
use App\Repository\BackpackDtoRepository;
use App\Security\Role;
use App\Workflow\WorkflowData;

class BackpackCounter
{

    /**
     * @var BackpackDtoRepository
     */
    private $backpackDtoRepository;

    private $backpackMakerDto;

    public function __construct(
        BackpackDtoRepository $backpackDtoRepository,
        User $user
    )
    {
        $this->backpackDtoRepository = $backpackDtoRepository;
        $this->backpackMakerDto=new BackpackMakerDto($user);
    }

    public function get(string $type,string $param=null)
    {
        return $this->backpackDtoRepository->countForDto(
            $this->backpackMakerDto->get($type,$param)
        );
    }

}
