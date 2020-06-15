<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DocumentationController extends AbstractController
{
    /**
     * @Route("/documentation", name="documentation")
     */
    public function index()
    {
        //les fichiers sont à déposer dans public/doc
        $docs = [[
            'name' => 'premier doc',
            'url' => 'DCGDR_PAR - recherche.pdf',
            'date' => '11/05/2020'
        ], [
            'name' => '3ème doc',
            'url' => 'doc.pdf',
            'date' => '10/05/2020'
        ]];

        return $this->render('documentation/index.html.twig', [
            'docs' => $docs,
        ]);
    }
}
