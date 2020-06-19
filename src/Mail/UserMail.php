<?php

namespace App\Mail;

use App\Entity\User;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class UserMail
{
    const LOGIN = 'user/login';
    const VALIDATE = 'user/validate';
    const REGISTRATION = 'user/register';
    const PASSWORDFORGET = 'user/password_forget';

    private $mail;

    public function __construct(Mail $mail)
    {
        $this->mail=$mail;
    }

    public function send(User $user,string $context,string $subject): int
    {
        if (!in_array($context, [self::LOGIN, self::VALIDATE, self::REGISTRATION, self::PASSWORDFORGET])) {
            return -1;
        }

        $this->mail
            ->setUserTo($user)
            ->setContext($context)
            ->setSubject($subject)
            ->setParamsTwig(['user'=>$user])
        ;

        return $this->mail->send();
    }

}
