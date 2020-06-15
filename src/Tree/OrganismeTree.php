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



    public function initialise($items): self
    {
        $this->items = $items;
        $this->item=null;

        if ($this->request->query->has('itemRequestId')) {
            $this->itemRequestId = $this->request->query->get('itemRequestId');
            $this->findItem();
        } else if (count($this->items)>0) {
            $this->item=$this->items[0];
        }

        return $this;
    }

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
                'text' => '<span class="text-primary">' . $item->getName() . '</span> ',
                'icon' => $this->icone. ' text-info ',
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