<?php

namespace App\Dto;

class AbstractDtoIsEnable extends AbstractDto
{
    /**
     * @var ?String
     */
    protected $isEnable;

    /**
     * @return mixed
     */
    public function getIsEnable()
    {
        return $this->isEnable;
    }

    protected function setIsEnable($isEnable)
    {
        $this->isEnable = $isEnable;
        return $this;
    }

}