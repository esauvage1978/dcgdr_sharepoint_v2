<?php

namespace App\Controller\Profil;

use App\Controller\AbstractGController;
use App\Entity\User;
use App\Mail\UserMail;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class RegistrationController extends AbstractGController
{

    /**
     * @route("/email/{token}", name="profil_email_validated")
     */
    public function profilEmailValidatedAction(
        string $token,
        UserRepository $userRepository,
        UserManager $userManager): Response
    {
        $user = $userRepository->findOneBy(['emailValidatedToken' => $token]);

        if (null === $user) {
            $this->addFlash('warning', 'L\'adresse d\'activation est incorrecte!');
        } else {
            $userManager->validateEmail($user);

            if ($userManager->save($user)) {
                $this->addFlash('success', 'Votre compte est activé. Vous pouvez vous connecter!');
            } else {
                $this->addFlash('danger', 'Echec de la mise à jour'.$userManager->getErrors($user));
            }
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/sendmail/emailvalidated", methods={"GET"}, name="profil_sendmail_email_validated")
     */
    public function sendmailActivationAction(UserMail $mail): Response
    {
        $user = $this->getUser();
        $mail->send($user, UserMail::VALIDATE, 'Validation de l\'email');
        $this->addFlash('success', 'Le mail est envoyé, merci de consulter votre messagerie.');

        return $this->redirectToRoute('home');
    }
}
