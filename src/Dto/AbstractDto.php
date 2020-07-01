<?php

namespace App\Dto;

class AbstractDto implements DtoInterface
{
    const FALSE = 'false';
    const TRUE = 'true';

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
    protected $name;

    /**
     * @var ?String
     */
    protected $id;

    /**
     * @var ?string
     */
    protected $visible;

    /**
     * @var ?string
     */
    protected $hide;


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

    /**
     * @return mixed
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param mixed $visible
     * @return AbstractDto
     * @throws \InvalidArgumentException
     */
    public function setVisible($visible)
    {
        $this->checkBool($visible);
        $this->visible = $visible;
        return $this;
    }

    protected function checkBool($value)
    {
        if (!in_array($value, [null, self::TRUE, self::FALSE])) {
            throw new \InvalidArgumentException('valeur interdite');
        }
    }

    /**
     * @return mixed
     */
    public function getHide()
    {
        return $this->hide;
    }

    /**
     * @param mixed $hide
     * @return AbstractDto
     */
    public function setHide($hide)
    {
        $this->checkBool($hide);
        $this->hide = $hide;
        return $this;
    }


}