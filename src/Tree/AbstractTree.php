<?php

namespace App\Tree;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractTree implements InterfaceTree
{
    /**
     * @var string
     */
    protected $idName;



    /**
     * @var array
     */
    protected $tree;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $parameter;

    /**
     * @var boolean
     */
    private $developed;

    /**
     * @var string
     */
    protected $itemRequestId;

    public function __construct(
        ContainerInterface $container,
        Request $request
    )
    {
        $this->container = $container;
        $this->request = $request;

        $this->idName='id';
        $tree = null;
        $this->developed = false;
        $this->parameter = [];
    }

    public function initialise($items): self
    {
        $this->items = $items;

        if(!isset($this->item)) {
            if ($this->request->query->has($this->idName)) {
                $this->itemRequestId = $this->request->query->get($this->idName);
                $this->findItem();
            } else if (count($this->items) > 0) {
                $this->item = $this->items[0];
            }
        }
        return $this;
    }

    protected function findItem()
    {
        foreach ($this->items as $item) {
            if ($this->itemRequestId === (string)$item->getId()) {
                $this->item = $item;
            }
        }

    }



    protected function getParent()
    {
        return '#';
    }

    protected function addBranche($id, $data_courant, $parent, $opened = true)
    {
        $this->tree[] = [
            'id' => $id,
            'parent' => $parent,
            'text' => $data_courant,
            'icon' => 'far fa-folder-open',
            'state' => [
                'opened' => $opened,
            ],
        ];
    }



    public function setParameter(array $parameter): self
    {
        $this->parameter = $parameter;
        return $this;
    }

    public function Developed(): self
    {
        $this->developed = true;
        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function setItem($item): self
    {
        $this->item = $item;
        return $this;
    }

    protected function getTreeCheck()
    {
        if (!isset($this->items)) {
            throw new InvalidParameterException('Vous devez initialiser la classe avec la fonction initialise');
        }
        if (!isset($this->item)) {
            throw new InvalidParameterException('La variable item n\'est pas définie');
        }
    }

    protected function generateUrl($id)
    {
        if (!isset($this->route)) {
            throw new InvalidParameterException('Aucune route définie');
        }

        $this->parameter = array_merge($this->parameter, [$this->idName => $id]);

        return $this->container->get('router')->generate($this->route, $this->parameter, UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    public function setRoute(string $route): self
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdName(): string
    {
        return $this->idName;
    }

    /**
     * @param string $idName
     */
    public function setIdName(string $idName): void
    {
        $this->idName = $idName;
    }
}