<?php

namespace App\Controller\Notification;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    /**
     * @Route("/notification/{id}", name="notification")
     */
    public function index(string $id)
    {
        // id =1
        switch($id) {
            case "1":
                return $this->redirectToRoute('user_login');
                break;
            case "2":
                return $this->redirectToRoute('profil_sendmail_email_validated');
                break;
            case "3":
                return $this->redirectToRoute('backpacks_draft');
                break;
            case "4":
                return $this->redirectToRoute('backpacks_mydraft');
                break;
            case "5":
                return $this->redirectToRoute('backpacks_news');
                break;
        }
    }
}
