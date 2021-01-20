<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function index()
    {
        $general_entries = [
            [
                'name' => 'Organisme',
                'route' => 'organisme_list',
                'content' => 'Gestion des organismes',
                'smallcontent' => 'Réservé au gestionnaire',
                'icon' => 'fas fa-building text-p-dark'
            ],
            [
                'name' => 'Corbeille',
                'route' => 'corbeille_list',
                'content' => 'Gestion des corbeilles',
                'smallcontent' => 'Réservé au gestionnaire',
                'icon' => 'fas fa-boxes text-p-dark'
            ],
            [
                'name' => 'Utilisateurs',
                'route' => 'user_list',
                'content' => 'Gestion des utilisateurs',
                'smallcontent' => 'réservé à l\'administrateur',
                'icon' => 'fa fa-users text-p-dark'
            ],
            [
                'name' => 'Informations générales',
                'route' => 'gpi_list',
                'content' => 'Gestion des données affichées sur les pages',
                'smallcontent' => 'Réservé au gestionnaire',
                'icon' => 'fas fa-bullhorn text-p-dark'
            ]
        ];

        $app_entries = [
            [
                'name' => 'Image de représentation',
                'route' => 'picture_list',
                'content' => 'des rubriques et des sous-rubriques',
                'smallcontent' => 'Réservé au gestionnaire',
                'icon' => 'far fa-image text-p-dark'
            ],
            [
                'name' => 'Thématique des rubriques',
                'route' => 'thematic_list',
                'content' => '',
                'smallcontent' => 'Réservé au gestionnaire',
                'icon' => 'fab fa-usb text-p-dark'
            ],
            [
                'name' => 'Rubriques',
                'route' => 'admin_rubric_list',
                'content' => '',
                'smallcontent' => 'Réservé au gestionnaire',
                'icon' => 'fas fa-project-diagram text-p-dark'
            ],
            [
                'name' => 'Thématique des sous-rubriques',
                'route' => 'underthematic_list',
                'content' => '',
                'smallcontent' => 'Réservé au gestionnaire',
                'icon' => 'fab fa-usb text-p-dark'
            ],
            [
                'name' => 'Sous-rubriques',
                'route' => 'admin_underrubric_list',
                'content' => '',
                'smallcontent' => 'Réservé au gestionnaire',
                'icon' => 'fas fa-project-diagram text-p-dark'
            ],
        ];



        $action_entries = [
            [
                'name' => 'Envoie les notifications',
                'route' => 'command_notificator',
                'content' => 'Envoie les notificatio par rapport aux abonnées',
                'smallcontent' => 'Réservé au gestionnaire',
                'icon' => 'fas fa-paper-plane text-p-dark'
            ]
        ];

        return $this->render('admin/index.html.twig', [
            'general_entries' => $general_entries,
            'app_entries' => $app_entries,
            'action_entries' => $action_entries,
        ]);
    }
}
