<?php

namespace App\Manager;

use App\Entity\Comment;
use App\Entity\EntityInterface;
use App\Entity\Mailer;
use App\Entity\User;
use App\Security\CurrentUser;
use App\Validator\BackpackValidator;
use Doctrine\ORM\EntityManagerInterface;

class CommentManager extends AbstractManager
{
    /**
     * @var CurrentUser
     */
    private $currentUser;

    public function __construct(
        EntityManagerInterface $manager,
        BackpackValidator $validator,
        CurrentUser $currentUser
    ) {
        parent::__construct($manager, $validator);
        $this->currentUser=$currentUser;
    }

    public function initialise(EntityInterface $entity): void
    {
        /**
         * @var Comment $bp
         */
        $bp=$entity;
        $bp->setSentAt(new \DateTime());

        if(empty( $bp->getId())) {
            $bp
                ->setUserFrom( $this->currentUser->getUser());
        }

    }

    public function initialiseForm($data): ?Comment
    {



        /** @var User $userFrom */
        $userFrom = $this->currentUser->getUser();
        $comment=new  Comment();
        $comment
            ->setUserFrom($userFrom)

            ->setSubject($data['subject'])
            ->setContent($data['content']);

        if(array_key_exists('usersTo',$data)) {
            $this->setUsers($data['usersTo'],$comment);
        }

        return $comment;
    }

    private function setUsers($data,Comment $comment)
    {
        dump($data);
        foreach ($data as $user) {
            $comment->addUsersTo($user);
        }
    }

}
