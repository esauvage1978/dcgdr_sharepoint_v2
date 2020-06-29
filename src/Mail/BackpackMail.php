<?php

namespace App\Mail;

use App\Entity\User;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class BackpackMail
{
    const NEW = 'backpack/new';
    const NOTIFICATOR='backpack/notificator';

    private $mail;

    public function __construct(Mail $mail)
    {
        $this->mail=$mail;
    }

    public function send(User $user,string $context,string $subject, array $paramsTwig =[]): int
    {
        if (!in_array($context, [self::NOTIFICATOR, self::NEW])) {
            return -1;
        }

        $userArray=['user'=>$user];

        $this->mail
            ->setUserTo($user)
            ->setContext($context)
            ->setSubject($subject)
            ->setParamsTwig(
                empty($paramsTwig)
                ?$userArray
            :array_merge($userArray,$paramsTwig))
        ;

        return $this->mail->send();
    }

}
