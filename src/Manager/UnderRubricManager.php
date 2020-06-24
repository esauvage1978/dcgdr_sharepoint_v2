<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\UnderRubricValidator;
use Doctrine\ORM\EntityManagerInterface;

class UnderRubricManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, UnderRubricValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
    }
}
