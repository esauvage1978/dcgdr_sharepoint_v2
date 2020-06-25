<?php

namespace App\Dto;

class AbstractDto implements DtoInterface
{
    const FALSE='false';
    const TRUE='true';

    /**
     * @var ?string
     */
    protected $wordSearch;

    /**
     * @var ?String
     */
    protected $page;

    /**
     * @var ?String
     */
    protected $isEnable;

    /**
     * @var ?String
     */
    protected $name;

    /**
     * @var ?String
     */
    protected $id;

    public function getWordSearch()
    {
        return $this->wordSearch;
    }

    public function setWordSearch($wordSearch)
    {
        $this->wordSearch = $wordSearch;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsEnable()
    {
        return $this->isEnable;
    }

    public function setIsEnable($isEnable)
    {
        $this->isEnable = $isEnable;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}