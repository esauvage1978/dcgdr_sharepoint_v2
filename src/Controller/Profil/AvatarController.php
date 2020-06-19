<?php


namespace App\Controller\Profil;

use App\Controller\AbstractGController;
use App\Repository\UserRepository;
use App\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
/**
 * @Route("/profil/avatar")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class AvatarController extends AbstractGController
{
    CONST DOMAINE='profil';

    /**
     * @Route("/", name="avatar")
     *
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function show(Request $request, UserRepository $userRepository): Response
    {
        return $this->render( self::DOMAINE . '/avatar.html.twig');
    }

    /**
     * @Route("/update", name="avatar_update")
     *
     * @param Request $request
     * @param UserManager $userManager
     * @return Response
     */
    public function ajaxAction(Request $request, UserManager $userManager)
    {
        /**
         * var $user User
         */
        $user = $this->getUser();

        /* on récupère la valeur envoyée par la vue */
        $image = $request->request->get('dataImg');

        $userManager->changeAvatar($user,$image);

        $response = new Response(json_encode([
            'retour' => 'Avatar mis à jour',
        ]));

        $response->headers->set('Content-Type', 'application/json');

        $this->addFlash(self::SUCCESS, self::MSG_MODIFY);

        return $response;
    }
}
