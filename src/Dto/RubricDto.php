<?php

namespace App\Dto;

use App\Entity\Rubric;
use App\Entity\Thematic;

class RubricDto extends AbstractDtoIsEnable
{

    /**
     * @var ?ThematicDto
     */
    private $thematicDto;

    /**
     * @var ?UnderThematicDto
     */
    private $underThematicDto;

    /**
     * @var ?UnderRubricDto
     */
    private $underRubricDto;

    /**
     * @var ?UserDto
     */
    private $userDto;

    /**
     * @return mixed
     */
    public function getThematicDto()
    {
        return $this->thematicDto;
    }

    /**
     * @param mixed $thematicDto
     * @return RubricDto
     */
    public function setThematicDto($thematicDto)
    {
        $this->thematicDto = $thematicDto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserDto()
    {
        return $this->userDto;
    }

    /**
     * @param mixed $userDto
     * @return RubricDto
     */
    public function setUserDto($userDto)
    {
        $this->userDto = $userDto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnderThematicDto()
    {
        return $this->underThematicDto;
    }

    /**
     * @param mixed $underThematicDto
     * @return RubricDto
     */
    public function setUnderThematicDto($underThematicDto)
    {
        $this->underThematicDto = $underThematicDto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnderRubricDto()
    {
        return $this->underRubricDto;
    }

    /**
     * @param mixed $underRubricDto
     * @return RubricDto
     */
    public function setUnderRubricDto($underRubricDto)
    {
        $this->underRubricDto = $underRubricDto;
        return $this;
    }






}
