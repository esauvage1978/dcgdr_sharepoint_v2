<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DocumentationController extends AbstractController
{
    /**
     * @Route("/documentation", name="documentation")
    * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        //les fichiers sont à déposer dans public/doc
        $docs = [[
            'name' => 'Guide pour la recherche',
            'url' => 'DCGDR_SHAREPOINT - recherche.pdf',
            'date' => '02/07/2020'
        ], [
            'name' => 'Guide du compte utilisateur',
            'url' => 'DCGDR_SHAREPOINT - compte utilisateur.pdf',
            'date' => '05/07/2020'
        ]];

        return $this->render('documentation/index.html.twig', [
            'docs' => $docs,
        ]);
    }
}
