<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class UserPasswordForgetEvent extends Event
{
    public const NAME = 'user.passwordforget';

    /**
     * @var User
     */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
