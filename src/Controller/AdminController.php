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
        ]];

        return $this->render('admin/index.html.twig', [
            'general_entries' => $general_entries,
        ]);
    }
}
