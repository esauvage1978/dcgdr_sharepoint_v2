<?php

/*
 * This file is part of the AdminLTE-Bundle demo.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use App\Repository\BackpackDtoRepository;
use App\Security\CurrentUser;
use App\Security\Role;
use App\Service\BackpackCounter;
use App\Service\BackpackMakerDto;
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
    private $auth;
    /**
     * @var CurrentUser
     */
    private $currentUser;

    /**
     * @var BackpackCounter
     */
    private $backpackCounter;


    /**
     * NotificationSubscriber constructor.
     * @param CurrentUser $currentUser
     * @param BackpackDtoRepository $backpackDtoRepository
     */
    public function __construct(
        CurrentUser $currentUser,
        BackpackDtoRepository $backpackDtoRepository
    )
    {
        $this->currentUser = $currentUser;

        if (null !== $currentUser->getUser()) {
            $this->backpackCounter = new BackpackCounter
            (
                $backpackDtoRepository,
                $this->currentUser->getUser()
            );
        }
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

        if (!$this->currentUser->isAuthenticatedRemember()) {
            $notification = new NotificationModel('Vous n\'êtes pas connecté !', Constants::TYPE_ERROR, 'fas fa-key');
            $notification->setId(1);
            $event->addNotification($notification);
            return;
        }

        if (!$this->currentUser->getUser()->getEmailValidated()) {
            $notification = new NotificationModel('Adresse mail non vérifiée !', Constants::TYPE_ERROR, 'fas fa-envelope');
            $notification->setId(2);
            $event->addNotification($notification);

        } elseif (Role::isUser($this->currentUser->getUser())) {

            $nbr = $this->backpackCounter->get(BackpackMakerDto::DRAFT);
            if ($nbr != "0") {
                $notification = new NotificationModel($nbr . ' brouillon' . ($nbr == "1" ? '' : 's'), Constants::TYPE_WARNING, 'fas fa-suitcase');
                $notification->setId(3);
                $event->addNotification($notification);
            }

            $nbr = $this->backpackCounter->get(BackpackMakerDto::MY_DRAFT);
            if ($nbr != "0") {
                $notification = new NotificationModel(($nbr == "1" ? 'Votre ' : 'vos ' . $nbr) . ' brouillon' . ($nbr == "1" ? '' : 's'), Constants::TYPE_WARNING, 'fas fa-suitcase');
                $notification->setId(4);
                $event->addNotification($notification);
            }

            $nbr = $this->backpackCounter->get(BackpackMakerDto::NEWS);
            if ($nbr != "0") {
                $notification = new NotificationModel('Les mises à jour récentes : ' . $nbr, Constants::TYPE_SUCCESS, 'fas fa-suitcase');
                $notification->setId(5);
                $event->addNotification($notification);
            }
        }
    }


}
