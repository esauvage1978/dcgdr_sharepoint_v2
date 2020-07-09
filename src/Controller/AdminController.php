<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $general_entries = [[
        'name' => 'Organisme',
            'route' => 'organisme_list'
        ],[
            'name' => 'Utilisateur',
            'route' => 'user_list'
        ],[
            'name' => 'Corbeille',
            'route' => 'corbeille_list'
        ],[
            'name' => 'Informations générales',
            'route' => 'gpi_list'
        ]];

        $app_entries = [[
            'name' => 'Image de présentation',
            'route' => 'picture_list'
        ],[
            'name' => 'Thématique des rubriques',
            'route' => 'thematic_list'
        ],[
            'name' => 'Rubriques',
            'route' => 'admin_rubric_list'
        ],[
            'name' => 'Thématique des sous-rubriques',
            'route' => 'underthematic_list'
        ],[
            'name' => 'Sous-rubriques',
            'route' => 'admin_underrubric_list'
        ]];

        $action_entries = [[
            'name' => 'Envoie les notifications',
            'route' => 'command_notificator'
        ]];

        return $this->render('admin/index.html.twig', [
            'general_entries' => $general_entries,
            'app_entries' => $app_entries,
            'action_entries' => $action_entries,
        ]);
    }
}
