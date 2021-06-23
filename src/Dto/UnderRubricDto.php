<?php

namespace App\Dto;


class UnderRubricDto extends AbstractDtoIsEnable
{
    /**
     * @var ?string
     */
    private $forUpdate;

    /**
     * @var ?UnderThematicDto
     */
    private $underThematicDto;

    /**
     * @var ?ThematicDto
     */
    private $ThematicDto;

    /**
     * @var ?RubricDto
     */
    private $rubricDto;

    /**
     * @var ?UserDto
     */
    private $userDto;

    /**
     * @return mixed
     */
    public function getUnderThematicDto()
    {
        return $this->underThematicDto;
    }

    /**
     * @param mixed $underThematicDto
     * @return UnderRubricDto
     */
    public function setUnderThematicDto($underThematicDto)
    {
        $this->underThematicDto = $underThematicDto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRubricDto()
    {
        return $this->rubricDto;
    }

    /**
     * @param mixed $rubricDto
     * @return UnderRubricDto
     */
    public function setRubricDto($rubricDto)
    {
        $this->rubricDto = $rubricDto;
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
     * @return UnderRubricDto
     */
    public function setUserDto($userDto)
    {
        $this->userDto = $userDto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getThematicDto()
    {
        return $this->ThematicDto;
    }

    /**
     * @param mixed $ThematicDto
     * @return UnderRubricDto
     */
    public function setThematicDto($ThematicDto)
    {
        $this->ThematicDto = $ThematicDto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getForUpdate()
    {
        return $this->forUpdate;
    }

    /**
     * @param mixed $forUpdate
     * @return UnderRubricDto
     */
    public function setForUpdate($forUpdate)
    {
        $this->forUpdate = $forUpdate;
        return $this;
    }
}
