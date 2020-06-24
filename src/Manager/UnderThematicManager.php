<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\UnderThematicValidator;
use Doctrine\ORM\EntityManagerInterface;

class UnderThematicManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, UnderThematicValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
    }
}
