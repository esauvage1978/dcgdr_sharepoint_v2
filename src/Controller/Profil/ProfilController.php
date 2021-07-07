<?php

namespace App\Controller\Profil;

use App\Controller\AbstractGController;
use App\Entity\User;
use App\Form\Profil\ProfilType;
use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
/**
 * @Route("/profil")
 * @IsGranted("ROLE_USER")
 */
class ProfilController extends AbstractGController
{
    /**
     * @Route("/", name="profil")
     *
     * @var Request $request
     * @var UserManager $userManager
     *
     * @return Response
     *
     */
    public function profileHomeAction(Request $request,  UserManager $manager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $oldUserMail=(clone $user)->getEmail();
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($manager->save($user,$oldUserMail)) {
                $this->addFlash(self::SUCCESS, self::MSG_MODIFY);
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $manager->getErrors($user));
            }
        }

        return $this->render('profil/index.html.twig', [
            'item' => $user,
            'form' => $form->createView()
        ]);
    }


}
