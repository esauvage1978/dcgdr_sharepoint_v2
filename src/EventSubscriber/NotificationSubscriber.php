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

/**
 * Class NotificationSubscriber adds notification messages to the top bar.
 */
class NotificationSubscriber implements EventSubscriberInterface
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $security;

    /**
     * @param AuthorizationCheckerInterface $security
     */
    public function __construct(AuthorizationCheckerInterface $security)
    {
        $this->security = $security;
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

        if (!$this->security->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $event->addNotification(new NotificationModel('Vous n\'êtes pas connecté !', Constants::TYPE_ERROR));
            return;
        }

        $notification = new NotificationModel('Vous êtes connecté!', Constants::TYPE_SUCCESS, 'fas fa-sign-in-alt');
        $notification->setId(2);
        $event->addNotification($notification);
    }
}
