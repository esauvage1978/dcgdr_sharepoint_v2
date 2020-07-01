<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\PictureValidator;
use Doctrine\ORM\EntityManagerInterface;

class PictureManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, PictureValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
    }
}
