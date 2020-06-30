<?php


namespace App\Helper;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class Stack
{

    /**
     * @var array
     */
    private $messages;

    public function toArray(): array
    {
        return $this->messages;
    }

    public function push(string $info)
    {
        array_push($this->messages,$info);
    }

    public function pushs(array $infos)
    {
        foreach ($infos as $info) {
            $this->push($info);
        }
    }

    public function clear()
    {
        $this->messages = [];
    }

    public function __construct()
    {
        $this->clear();
    }
}