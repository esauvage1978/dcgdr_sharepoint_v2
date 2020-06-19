<?php

/*
 * This file is part of the AdminLTE-Bundle demo.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use App\Entity\User;
use KevinPapst\AdminLTEBundle\Event\NotificationListEvent;
use KevinPapst\AdminLTEBundle\Helper\Constants;
use KevinPapst\AdminLTEBundle\Model\NotificationModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class NotificationSubscriber adds notification messages to the top bar.
 */
class NotificationSubscriber implements EventSubscriberInterface
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $auth;
    /**
     * @var Security
     */
    private $security;
    /**
     * @param AuthorizationCheckerInterface $auth
     */
    public function __construct(AuthorizationCheckerInterface $auth, Security $security)
    {
        $this->auth = $auth;
        $this->security=$security;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            NotificationListEvent::class => ['onNotifications', 100],
        ];
    }

    /**
     * @param NotificationListEvent $event
     */
    public function onNotifications(NotificationListEvent $event)
    {

        if (!$this->auth->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $notification = new NotificationModel('Vous n\'êtes pas connecté !', Constants::TYPE_ERROR, 'fas fa-key');
            $notification->setId(1);
            $event->addNotification($notification);
            return;
        }

        if (!$this->security->getUser()->getEmailValidated()) {
            $notification = new NotificationModel('Adresse mail non vérifiée !', Constants::TYPE_ERROR, 'fas fa-envelope');
            $notification->setId(2);
            $event->addNotification($notification);

        }
    }
}
