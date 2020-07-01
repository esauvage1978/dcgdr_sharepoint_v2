<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\OrganismeValidator;
use Doctrine\ORM\EntityManagerInterface;

class OrganismeManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager,OrganismeValidator $validator)
    {
        parent::__construct($manager,$validator);
    }

    public function initialise(EntityInterface $entity): void
    {
        if(empty($entity->getRef())) {
            $entity->setRef('000');
        }
    }
}
