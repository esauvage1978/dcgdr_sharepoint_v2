<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\ThematicValidator;
use Doctrine\ORM\EntityManagerInterface;

class ThematicManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, ThematicValidator $validator)
    {
        parent::__construct($manager, $validator);
    }


    public function initialise(EntityInterface $entity): void
    {
    }
}
