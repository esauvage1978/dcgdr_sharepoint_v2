<?php

namespace App\Security;

use App\Entity\UnderRubric;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class UnderRubricVoter extends Voter
{
    const READ = 'read';
    const UPDATE = 'update';

    /**
     * @var User|null
     */
    private $user;

    public function __construct(CurrentUser $currentUser)
    {
        $this->user = $currentUser->getUser();
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::READ, self::UPDATE])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (null !== $subject and !$subject instanceof UnderRubric) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if (!$this->user instanceof User) {
            return false;
        }

        /** @var UnderRubric $underrubric */
        $underrubric = $subject;

        switch ($attribute) {
            case self::READ:
                return $this->canRead($underrubric, $this->user);
            case self::UPDATE:
                return $this->canUpdate($underrubric, $this->user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    public function canRead(UnderRubric $underrubric, User $user)
    {
        if (Role::isGestionnaire($this->user)) {
            return true;
        }

        if ($underrubric->getIsShowAll()) {
            return true;
        }

        foreach ($underrubric->getReaders() as $corbeille) {
            if (in_array($user, $corbeille->getUsers()->toArray())) {
                return true;
            }
        }
        foreach ($underrubric->getRubric() as $rubric) {
            if ($rubric->getIsShowAll()) {
                return true;
            }

            foreach ($rubric->getReaders() as $corbeille) {
                if (in_array($user, $corbeille->getUsers()->toArray())) {
                    return true;
                }
            }

            foreach ($rubric->getWriters() as $corbeille) {
                if (in_array($user, $corbeille->getUsers()->toArray())) {
                    return true;
                }
            }

        }

        return $this->canUpdate($underrubric, $user);
    }

    public function canUpdate(UnderRubric $underrubric, User $user)
    {
        dump('cu');
        if (Role::isGestionnaire($this->user)) {
            return true;
        }

        foreach ($underrubric->getWriters() as $corbeille) {
            if (in_array($user, $corbeille->getUsers()->toArray())) {
                return true;
            }
        }

        return false;
    }

}
