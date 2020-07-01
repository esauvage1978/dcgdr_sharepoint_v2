<?php

namespace App\EventSubscriber;

use App\Event\UserPasswordForgetEvent;
use App\Event\UserRegistrationEvent;
use App\Mail\UserMail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserMail
     */
    private $userMail;

    public function __construct(UserMail $userMail)
    {
        $this->userMail = $userMail;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            UserRegistrationEvent::NAME => 'onUserRegistration',
            UserPasswordForgetEvent::NAME => 'onUserPasswordForget',
        ];
    }

    public function onUserRegistration(UserRegistrationEvent $event): int
    {
        return $this->userMail->send($event->getUser(), UserMail::REGISTRATION, 'Validation de votre compte');
    }

    public function onUserPasswordForget(UserPasswordForgetEvent $event): int
    {
        return $this->userMail->send($event->getUser(), UserMail::PASSWORDFORGET, 'Mot de passe oublié');
    }
}
