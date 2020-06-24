<?php

namespace App\Dto;


class UnderRubricDto extends AbstractDto
{

    /**
     * @var ?UnderThematicDto
     */
    public $underThematicDto;

    /**
     * @var ?RubricDto
     */
    public $rubricDto;

    /**
     * @var ?UserDto
     */
    public $userDto;

    public function __construct()
    {
        $this->underThematicDto=new UnderThematicDto();
        $this->userDto=new UserDto();
        $this->rubricDto=new RubricDto();
    }

}
