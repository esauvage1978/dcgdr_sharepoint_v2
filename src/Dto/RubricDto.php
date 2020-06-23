<?php

namespace App\Dto;

use App\Entity\Rubric;
use App\Entity\Thematic;

class RubricDto extends AbstractDto
{

    /**
     * @var ?ThematicDto
     */
    public $thematicDto;

    /**
     * @var ?UserDto
     */
    public $userDto;

    public function __construct()
    {
        $this->thematicDto=new ThematicDto();
        $this->userDto=new UserDto();
    }

}
