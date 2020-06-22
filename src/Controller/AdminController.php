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
        ]];

        $app_entries = [[
            'name' => 'Image de présentation',
            'route' => 'picture_list'
        ],[
            'name' => 'Thématique des rubriques',
            'route' => 'thematic_list'
        ]];

        return $this->render('admin/index.html.twig', [
            'general_entries' => $general_entries,
            'app_entries' => $app_entries,
        ]);
    }
}
