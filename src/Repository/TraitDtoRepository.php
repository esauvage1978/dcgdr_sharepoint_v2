<?php


namespace App\Repository;


use App\Dto\DtoInterface;
use Doctrine\ORM\QueryBuilder;

trait  TraitDtoRepository
{

    /**
     * @var QueryBuilder
     */
    private $builder;

    /**
     * @var array
     */
    private $params;

    private function addParams($key, $value)
    {
        $onevalue = [$key => $value];
        if (empty($this->params)) {
            $this->params = $onevalue;
        } else {
            $this->params = array_merge($onevalue, $this->params);
        }
    }
}