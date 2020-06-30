<?php


namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;

final class CurrentUser
{

    /**
     * @var User|null
     */
    private $user;

    /**
     * @var Security
     */
    private $security;

    /**
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security=$security;
        $this->user=$security->getUser();
    }


    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    public function isAuthenticatedRemember():bool
    {
        return $this->security->isGranted('IS_AUTHENTICATED_REMEMBERED')   ;
    }
}
