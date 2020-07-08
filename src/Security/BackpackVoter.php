<?php

namespace App\Security;

use App\Entity\Backpack;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class BackpackVoter extends Voter
{
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /**
     * @var User|null $currentUser
     */
    private $user;

    public function __construct(CurrentUser $currentUser)
    {
        $this->user = $currentUser->getUser();
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::READ, self::UPDATE, self::DELETE])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (null !== $subject and !$subject instanceof Backpack) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        if (!$this->user instanceof User) {
            return false;
        }

        /** @var Backpack $backpack */
        $backpack = $subject;

        switch ($attribute) {
            case self::READ:
                return $this->canRead($backpack, $this->user);
            case self::UPDATE:
                return $this->canUpdate($backpack, $this->user);
            case self::DELETE:
                return $this->canDelete($backpack, $this->user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    public function canRead(Backpack $backpack, User $user)
    {
        if (Role::isGestionnaire($this->user)) {
            return true;
        }

        if ($backpack->getUnderRubric()->getIsShowAll()) {
            return true;
        }

        foreach ($backpack->getUnderRubric()->getReaders() as $corbeille) {
            if (in_array($user, $corbeille->getUsers()->toArray())) {
                return true;
            }
        }

        if ($backpack->getUnderRubric()->getRubric()->getIsShowAll()) {
            return true;
        }

        foreach ($backpack->getUnderRubric()->getRubric()->getReaders() as $corbeille) {
            if (in_array($user, $corbeille->getUsers()->toArray())) {
                return true;
            }
        }

        return $this->canUpdate($backpack, $user);
    }

    public function canUpdate(Backpack $backpack, User $user)
    {
        if (Role::isGestionnaire($this->user)) {
            return true;
        }

        foreach ($backpack->getUnderRubric()->getWriters() as $corbeille) {
            if (in_array($user, $corbeille->getUsers()->toArray())) {
                return true;
            }
        }

        foreach ($backpack->getUnderRubric()->getRubric()->getWriters() as $corbeille) {
            if (in_array($user, $corbeille->getUsers()->toArray())) {
                return true;
            }
        }

        return false;
    }

    public function canDelete(Backpack $backpack, User $user)
    {
        if (Role::isUser($this->user)) {
            return true;
        }

        return false;
    }

}
