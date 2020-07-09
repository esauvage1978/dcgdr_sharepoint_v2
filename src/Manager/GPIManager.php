<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Entity\User;
use App\Security\CurrentUser;
use App\Validator\GPIValidator;
use Doctrine\ORM\EntityManagerInterface;

class GPIManager extends AbstractManager
{
    /**
     * @var User $user
     */
    private $user;

    public function __construct(
        EntityManagerInterface $manager,
        GPIValidator $validator,
        CurrentUser $currentUser)
    {
        parent::__construct($manager, $validator);
        $this->user=$currentUser->getUser();
    }

    public function initialise(EntityInterface $entity): void
    {
        $entity->setUser($this->user);
        $entity->setModifyAt(new \DateTime());
    }
}
