<?php

namespace App\Dto;


use Symfony\Component\HttpFoundation\Request;

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
     * @var ?string
     */
    private $isNew;

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

    /**
     * @return mixed
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * @param mixed $isNew
     * @return BackpackDto
     */
    public function setIsNew($isNew)
    {
        $this->checkBool($isNew);
        $this->isNew = $isNew;
        return $this;
    }

    public function getData(): array
    {
        $d=[];
        isset($this->wordSearch) && $d=array_merge($d,['wordSearch'=>$this->wordSearch]);
        isset($this->visible) && $d=array_merge($d,['isNew'=>$this->isNew]);
        isset($this->visible) && $d=array_merge($d,['visible'=>$this->visible]);
        isset($this->visible) && $d=array_merge($d,['currentState'=>$this->currentState]);
        isset($this->hide) && $d=array_merge($d,['hide'=>$this->hide]);
        isset($this->ownerDto) && isset($this->ownerDto->id) && $d=array_merge($d,['owner'=>$this->ownerDto->id]);
        isset($this->underRubricDto) && isset($this->underRubricDto->id) && $d=array_merge($d,['underRubric'=>$this->underRubricDto->id]);
        isset($this->rubricDto) && isset($this->rubricDto->id) && $d=array_merge($d,['rubric'=>$this->rubricDto->id]);
        return $d;
    }
    public function setData(Request $datas)
    {
        null!==$datas->get('wordSearch') && $this->wordSearch=$datas->get('wordSearch');
        null!==$datas->get('isNew') && $this->isNew=$datas->get('isNew');
        null!==$datas->get('visible') && $this->visible=$datas->get('visible');
        null!==$datas->get('hide') && $this->hide=$datas->get('hide');
        null!==$datas->get('owner') && $this->ownerDto=(new UserDto())->setId($datas->get('owner'));
        null!==$datas->get('currentState') && $this->currentState=$datas->get('currentState');
        null!==$datas->get('underRubric') && $this->underRubricDto=(new UnderRubricDto())->setId($datas->get('underRubric'));
        null!==$datas->get('rubric') && $this->rubricDto=(new RubricDto())->setId($datas->get('rubric'));
    }
}
