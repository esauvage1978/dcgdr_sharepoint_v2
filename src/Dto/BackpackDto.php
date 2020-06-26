<?php

namespace App\Dto;


class BackpackDto extends AbstractDto
{

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
     * @var ?UnderRubricDto
     */
    private $underRubricDto;

    /**
     * @var ?UserDto
     */
    private $userDto;

    /**
     * @var ?UserDto
     */
    private $ownerDto;

    /**
     * @var ?string
     */
    private $currentState;

    /**
     * @return mixed
     */
    public function getUnderThematicDto()
    {
        return $this->underThematicDto;
    }

    /**
     * @param mixed $underThematicDto
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
     */
    public function setThematicDto($ThematicDto)
    {
        $this->ThematicDto = $ThematicDto;
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
     * @return BackpackDto
     */
    public function setUnderRubricDto($underRubricDto)
    {
        $this->underRubricDto = $underRubricDto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOwnerDto()
    {
        return $this->ownerDto;
    }

    /**
     * @param mixed $ownerDto
     * @return BackpackDto
     */
    public function setOwnerDto($ownerDto)
    {
        $this->ownerDto = $ownerDto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentState()
    {
        return $this->currentState;
    }

    /**
     * @param mixed $currentState
     * @return BackpackDto
     */
    public function setCurrentState($currentState)
    {
        $this->currentState = $currentState;
        return $this;
    }

}
