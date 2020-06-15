<?php

namespace App\Tree;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractTree implements InterfaceTree
{
    /**
     * @var array
     */
    protected $tree;

    protected $items;
    protected $item;

    /**
     * @var string
     */
    protected $icone;





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
        $this->request=$request;

        $tree = null;
        $this->developed=false;
        $this->parameter=[];
    }

    protected function findItem()
    {
        foreach ($this->items as $item) {
            if ($this->itemRequestId === $item->getId()) {
                $this->item=$item;
            }
        }
    }

    protected function generateUrl($backpackRequestId)
    {
        if (isset($this->route)) {
            $this->parameter=array_merge( $this->parameter,  ['itemRequestId' => $backpackRequestId]);
            return $this->container->get('router')->generate($this->route, $this->parameter, UrlGeneratorInterface::ABSOLUTE_PATH);
        }
        return '';
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

    public function setRoute(string $route): self
    {
        $this->route = $route;
        return $this;
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

    /**
     * @param string $icone
     */
    public function setIcone(string $icone): void
    {
        $this->icone = $icone;
    }

    protected function getTreeCheck()
    {
        if(!isset($this->items)) {
            throw new InvalidParameterException('Vous devez initialiser la classe avec la fonction initialise');
        }
    }
}