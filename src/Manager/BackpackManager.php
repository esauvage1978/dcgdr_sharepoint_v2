<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\BackpackValidator;
use App\Workflow\WorkflowData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class BackpackManager extends AbstractManager
{
    private $security;
    public function __construct(
        EntityManagerInterface $manager,
        BackpackValidator $validator,
        Security $security
    ) {
        parent::__construct($manager, $validator);
        $this->security=$security;
    }

    public function initialise(EntityInterface $entity): void
    {
        $entity->setUpdateAt(new \DateTime());
        if(empty( $entity->getId())) {
            $entity
                ->setOwner( $this->security->getUser())
                ->setCurrentPlace(WorkflowData::STATE_DRAFT);
            }
    }


}
