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
        $entries = [[
            'name' => 'Organisme',
            'route' => 'organisme_list'
        ]];

        return $this->render('admin/index.html.twig', [
            'entries' => $entries,
        ]);
    }
}
