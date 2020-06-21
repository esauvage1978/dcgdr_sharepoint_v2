<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\CorbeilleValidator;
use Doctrine\ORM\EntityManagerInterface;

class CorbeilleManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager,CorbeilleValidator $validator)
    {
        parent::__construct($manager,$validator);
    }

    public function initialise(EntityInterface $entity): void
    {

    }
}
