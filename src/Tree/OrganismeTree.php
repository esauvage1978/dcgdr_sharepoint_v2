<?php


namespace App\Tree;


use App\Entity\Organisme;

class OrganismeTree extends AbstractTree
{
    /**
     * @var Organisme[]
     */
    protected $items;
    /**
     * @var Organisme
     */
    protected $item;

    /**
     * @param $path
     * @param string $parent
     * @param bool $baseFolderName
     *
     * @return array|null
     */
    public function getTree()
    {
        $this->getTreeCheck();
        foreach ($this->items as $item) {

            $open = $this->item->getId() === $item->getId();

            $this->tree[] = [
                'id' => $item->getid(),
                'parent' => $this->getParent(),
                'text' => '<span class="'. ($item->getIsEnable()?'text-primary':'text-warning').'">'
                    .$item->getRef(). ' '. $item->getName() . '</span> ',
                'icon' =>  'fas fa-building ' . ($item->getIsEnable()?'text-primary':'text-warning'),
                'a_attr' => [
                    'href' => $this->generateUrl($item->getId()),
                ],
                'state' => [
                    'selected' => $open,
                    'opened' => true,
                ],
            ];

        }

        return json_encode($this->tree);
    }


}