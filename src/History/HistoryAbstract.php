<?php

namespace App\History;

use App\Entity\History;
use App\Manager\HistoryManager;
use Symfony\Component\Security\Core\Security;

abstract class HistoryAbstract
{
    /**
     * @var HistoryManager
     */
    private $manager;

    /**
     * @var History
     */
    protected $history;

    /**
     * @var array
     */
    private $content;

    /**
     * @var Security
     */
    private $securityContext;

    public function __construct(
        HistoryManager $manager,
        Security $securityContext
    )
    {
        $this->manager = $manager;
        $this->securityContext = $securityContext;
        $this->history=new History();
        $this->content=[];
    }

    private function loadUser()
    {
        return $this->securityContext->getToken()->getUser();
    }

    protected function compareField(string $field, ?string $oldData, ?string $newData): bool
    {
        if (!isset($oldData) && !isset($newData) ) {
            return false;
        }
        
        if($oldData!==$newData) {
            $this->addContent($field,$oldData,$newData);
            return true;
        }
        return false;
    }

    protected function compareFieldBool(string $field, ?string $oldData, ?string $newData): bool
    {
        if($oldData!==$newData) {
            $this->addContent($field,$oldData?'Oui':'Non',$newData?'Oui':'Non');
            return true;
        }
        return false;
    }

    protected function compareFieldOneToOne(string $field,string $fieldEntity, ?object $oldData, ?object $newData): bool
    {
        if($oldData!==$newData) {
            $func='get'. $fieldEntity;
            $oldDataValue=empty($oldData)?'':($oldData->$func());
            $newDataValue=empty($newData)?'':($newData->$func());
            $this->addContent($field,$oldDataValue,$newDataValue);
            return true;
        }
        return false;
    }


    protected function addContent(string $field, ?string $oldData, ?string $newData)
    {
        array_push($this->content,[
           'field'=>$field,
            'oldData'=>(empty($oldData)?'':$oldData),
            'newData'=>(empty($newData)?'':$newData)
        ]);
    }

    protected function save()
    {
        $this->history
            ->setCreatedAt(new \DateTime())
            ->setUser($this->loadUser())
            ->setContent($this->content);

        $this->manager->save($this->history);
    }
}