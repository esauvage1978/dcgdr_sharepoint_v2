<?php

namespace App\Controller;

use App\Command\NotificatorCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificatorController extends AbstractController
{
    /**
     * @Route("/command/notificator", name="command_notificator", methods={"GET"})
     *
     * @IsGranted("ROLE_GESTIONNAIRE")
     *
     * @return Response
     */
    public function commandNotificator(NotificatorCommand $notificatorCommand)
    {
        $notificatorCommand->runTraitement();

        $this->addFlash('info', $notificatorCommand->getMessagesForAlert());

        return $this->redirectToRoute('admin');
    }
}
