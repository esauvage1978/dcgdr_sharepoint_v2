<?php

namespace App\Mail;

use App\Helper\ParamsInServices;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class MailerTransport
{
    /**
     * @var EsmtpTransport
     */
    private $transport;

    /**
     * @var ParamsInServices
     */
    private $paramsInServices;

    public function __construct( ParamsInServices $paramsInServices)
    {
        $this->paramsInServices=$paramsInServices;
    }

    public function getTransport()
    {
        $this->transport = new EsmtpTransport(
            $this->paramsInServices->get(ParamsInServices::MAILER_SMTP_HOST),
            $this->paramsInServices->get(ParamsInServices::MAILER_SMTP_PORT)
        );
        $this->transport->setUsername($this->paramsInServices->get(ParamsInServices::MAILER_SMTP_USERNAME));
        $this->transport->setPassword($this->paramsInServices->get(ParamsInServices::MAILER_SMTP_PASSWORD));

        return $this->transport;
    }
}