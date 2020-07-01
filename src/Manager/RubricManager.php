<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\RubricValidator;
use Doctrine\ORM\EntityManagerInterface;

class RubricManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, RubricValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
    }
}
