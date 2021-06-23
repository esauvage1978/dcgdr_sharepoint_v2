<?php

namespace App\Helper;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

class UserByCorbeilles
{

    private $users;

    public function __construct()
    {
    }

    public function getUsers($corbeilles)
    {


        $this->users = new ArrayCollection();
        foreach ($corbeilles as $corbeille) {
            $this->setUsers($corbeille->getUsers());
        }
        $datas = $this->users->toArray();
        usort($datas, function ($a, $b) {
            return strcmp($a->getName(), $b->getName());
        });

        return $datas;
    }

    private function setUsers($data)
    {
        foreach ($data as $user) {
            $this->addUser($user);
        }
    }

    static function orderByName($a, $b)
    {
        return strcmp($a->getName(), $b->getName());
    }


    public function getUsersEmailTo()
    {
        return $this->users;
    }

    private function addUser(User $user)
    {
        if ($user->getIsEnable()) {
            if (!$this->users->contains($user)) {
                $this->users[] = $user;
            }
        }
    }
}
