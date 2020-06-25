<?php


namespace App\Tree;


use App\Entity\Backpack;
use App\Repository\BackpackRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BackpackTree extends AbstractTree
{
    /**
     * @var Backpack[]
     */
    protected $items;
    /**
     * @var Backpack
     */
    protected $item;


    /**
     * @var bool
     */
    private $hideThematic=false;


    /**
     * @var bool
     */
    private $hideRubric=false;


    /**
     * @var bool
     */
    private $hideUnderThematic=false;

    private $thematic_last = '';
    private $thematic_id = '';
    private $rubric_last = '';
    private $rubric_id = '';
    private $underThematic_last = '';
    private $underThematic_id = '';
    private $underRubric_last = '';
    private $underRubric_id = '';

    private $dir1_last = '';
    private $dir1_id = '';
    private $dir1_i = 0;
    private $dir2_last = '';
    private $dir2_id = '';
    private $dir2_i = 0;
    private $dir3_last = '';
    private $dir3_id = '';
    private $dir3_i = 0;
    private $dir4_last = '';
    private $dir4_id = '';
    private $dir4_i = 0;
    private $dir5_last = '';
    private $dir5_id = '';
    private $dir5_i = 0;


    private function check()
    {
        if ($this->hideRubric ) {
            $this->hideThematic = true;
        }
        if ($this->hideUnderThematic) {
            $this->hideThematic = true;
            $this->hideRubric = true;
        }

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

            if(!$this->hideThematic) {
                $this->thematic($item);
            }
            if(!$this->hideRubric) {
                $this->rubric($item);
            }
            if(!$this->hideUnderThematic) {
                $this->underThematic($item);
            }
            $this->underRubric($item);
            $this->Dir1($item);
            $this->Dir2($item);
            $this->Dir3($item);
            $this->Dir4($item);
            $this->Dir5($item);

            $filesNumber = 0;//$backpack->getBackpackFiles()->count()+$backpack->getBackpackLinks()->count();
            $fileSpan = $filesNumber > 0 ? " <span class='label label-default'>{$filesNumber}</span>" : '';

            $this->tree[] = [
                'id' => $item->getid(),
                'parent' => $this->getParent(),
                'text' => '<span class="text-primary">' . $item->getName() . '</span> '.$fileSpan,
                'icon' => 'fas fa-suitcase text-info ',
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

    protected function getParent()
    {
        if($this->dir5_id!='') {
            return $this->dir5_id;
        }
        if($this->dir4_id!='') {
            return $this->dir4_id;
        }
        if($this->dir3_id!='') {
            return $this->dir3_id;
        }
        if($this->dir2_id!='') {
            return $this->dir2_id;
        }
        if($this->dir1_id!='') {
            return $this->dir1_id;
        }
        return $this->underRubric_id;
    }

    private function thematic(Backpack $backpack)
    {
        $data_courant = $backpack->getUnderRubric()->getRubric()->getThematic()->getName();
        $this->thematic_id = 't' . $backpack->getUnderRubric()->getRubric()->getThematic()->getid();

        if ($data_courant != $this->thematic_last) {
            $this->addBranche($this->thematic_id, $data_courant, '#');
        }

        $this->thematic_last = $data_courant;
    }
    private function rubric(Backpack $backpack)
    {
        $data_courant = $backpack->getUnderRubric()->getRubric()->getName();
        $this->rubric_id = 'r' . $backpack->getUnderRubric()->getRubric()->getid();

        if ($data_courant != $this->rubric_last) {
            $parent = $this->hideThematic ? '#': $this->thematic_id ;
            $this->addBranche($this->rubric_id, $data_courant, $parent, $this->developed);
        }

        $this->rubric_last = $data_courant;
    }
    private function underThematic(Backpack $backpack)
    {

        $data_courant = $backpack->getUnderRubric()->getUnderThematic()->getName();
        $this->underThematic_id = 'ut' . $backpack->getUnderRubric()->getUnderThematic()->getid();

        if ($data_courant != $this->underThematic_last) {
            $parent = !$this->hideRubric ? $this->rubric_id : (!$this->hideThematic ? $this->thematic_id : '#');
            $this->addBranche($this->underThematic_id, $data_courant, $parent, $this->developed);
        }

        $this->underThematic_last = $data_courant;
    }
    private function underRubric(Backpack $backpack)
    {
        $data_courant = $backpack->getUnderRubric()->getName();
        $this->underRubric_id = 'ur_' . $backpack->getUnderRubric()->getid();

        if ($data_courant != $this->underRubric_last) {
            $parent = !$this->hideUnderThematic ? $this->underThematic_id : (!$this->hideRubric ? $this->rubric_id : (!$this->hideThematic ? $this->thematic_id : '#'));
            $this->addBranche($this->underRubric_id, $data_courant, $parent, $this->developed);

            $this->dir1_id = '';
            $this->dir1_last = '';
            $this->dir2_id = '';
            $this->dir2_last = '';
            $this->dir3_id = '';
            $this->dir3_last = '';
            $this->dir4_id = '';
            $this->dir4_last = '';
            $this->dir5_id = '';
            $this->dir5_last = '';
        }

        $this->underRubric_last = $data_courant;
    }
    private function Dir1(Backpack $backpack)
    {
        $data_courant = $backpack->getDir1();

        if ($data_courant==='' || $data_courant===null) {
            $this->dir1_id = '';
            $this->dir1_last = '';
            $this->dir2_id = '';
            $this->dir2_last = '';
            $this->dir3_id = '';
            $this->dir3_last = '';
            $this->dir4_id = '';
            $this->dir4_last = '';
            $this->dir5_id = '';
            $this->dir5_last = '';
            return ;
        }

        if ($data_courant != $this->dir1_last) {
            $this->dir1_i++;
            $this->dir1_id =  'd1_' . $this->dir1_i;

            $parent = $this->underRubric_id;
            $this->addBranche($this->dir1_id, $data_courant, $parent, $this->developed);

            $this->dir1_last = $data_courant;
        }

    }
    private function Dir2(Backpack $backpack)
    {
        $data_courant = $backpack->getDir2();

        if ($data_courant==='' || $data_courant===null) {
            $this->dir2_id = '';
            $this->dir2_last = '';
            $this->dir3_id = '';
            $this->dir3_last = '';
            $this->dir4_id = '';
            $this->dir4_last = '';
            $this->dir5_id = '';
            $this->dir5_last = '';
            return ;
        }

        if ($data_courant != $this->dir2_last) {
            $this->dir2_i++;
            $this->dir2_id =  'd2_' . $this->dir2_i;

            $parent = $this->dir1_id;
            $this->addBranche($this->dir2_id, $data_courant, $parent, $this->developed);

            $this->dir2_last = $data_courant;
        }

    }
    private function Dir3(Backpack $backpack)
    {
        $data_courant = $backpack->getDir3();

        if ($data_courant==='' || $data_courant===null) {
            $this->dir3_id = '';
            $this->dir3_last = '';
            $this->dir4_id = '';
            $this->dir4_last = '';
            $this->dir5_id = '';
            $this->dir5_last = '';
            return ;
        }

        if ($data_courant != $this->dir3_last) {
            $this->dir3_i++;
            $this->dir3_id =  'd3_' . $this->dir3_i;

            $parent = $this->dir2_id;
            $this->addBranche($this->dir3_id, $data_courant, $parent, $this->developed);

            $this->dir3_last = $data_courant;
        }

    }
    private function Dir4(Backpack $backpack)
    {
        $data_courant = $backpack->getDir4();

        if ($data_courant==='' || $data_courant===null) {
            $this->dir4_id = '';
            $this->dir4_last = '';
            $this->dir5_id = '';
            $this->dir5_last = '';
            return ;
        }

        if ($data_courant != $this->dir4_last) {
            $this->dir4_i++;
            $this->dir4_id =  'd4_' . $this->dir4_i;

            $parent = $this->dir3_id;
            $this->addBranche($this->dir4_id, $data_courant, $parent, $this->developed);

            $this->dir4_last = $data_courant;
        }

    }
    private function Dir5(Backpack $backpack)
    {
        $data_courant = $backpack->getDir5();

        if ($data_courant==='' || $data_courant===null) {
            $this->dir5_id = '';
            $this->dir5_last = '';
            return ;
        }

        if ($data_courant != $this->dir5_last) {
            $this->dir5_i++;
            $this->dir5_id =  'd5_' . $this->dir5_i;

            $parent = $this->dir4_id;
            $this->addBranche($this->dir5_id, $data_courant, $parent, $this->developed);

            $this->dir5_last = $data_courant;
        }

    }


    public function hideThematic(): self
    {
        $this->hideThematic = true;
        $this->check();
        return $this;
    }
    public function hideRubric(): self
    {
        $this->hideRubric = true;
        $this->check();
        return $this;
    }
    public function hideUnderThematic(): self
    {
        $this->hideUnderThematic = true;
        $this->check();
        return $this;
    }

}