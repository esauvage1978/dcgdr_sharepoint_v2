<?php

namespace App\Security;

use App\Entity\Rubric;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RubricVoter extends Voter
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
        if (null !== $subject and !$subject instanceof Rubric) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute( $attribute, $subject, TokenInterface $token)
    {
        if (!$this->user instanceof User) {
            return false;
        }

        /** @var Rubric $rubric */
        $rubric = $subject;

        switch ($attribute) {
            case self::READ:
                return $this->canRead($rubric, $this->user);
            case self::UPDATE:
                return $this->canUpdate($rubric, $this->user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    public function canRead(Rubric $rubric, User $user)
    {
        if (Role::isGestionnaire($this->user)) {
            return true;
        }

        if ($rubric->getIsShowAll()) {
            return true;
        }

        foreach ($rubric->getReaders() as $corbeille) {
            if (in_array($user, $corbeille->getUsers()->toArray())) {
                return true;
            }
        }
        foreach ($rubric->getUnderRubrics() as $underRubric) {
            if ($underRubric->getIsShowAll()) {
                return true;
            }

            foreach ($underRubric->getReaders() as $corbeille) {
                if (in_array($user, $corbeille->getUsers()->toArray())) {
                    return true;
                }
            }

            foreach ($underRubric->getWriters() as $corbeille) {
                if (in_array($user, $corbeille->getUsers()->toArray())) {
                    return true;
                }
            }

        }

        return $this->canUpdate($rubric, $user);
    }

    public function canUpdate(Rubric $rubric, User $user)
    {
        if (Role::isGestionnaire($this->user)) {
            return true;
        }

        foreach ($rubric->getWriters() as $corbeille) {
            if (in_array($user, $corbeille->getUsers()->toArray())) {
                return true;
            }
        }

        return false;
    }

}
