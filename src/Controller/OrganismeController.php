<?php

namespace App\Controller;

use App\Tree\OrganismeTree;
use App\Repository\OrganismeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrganismeController extends AbstractController
{
    /**
     * @Route("/organisme", name="organismes")
     */
    public function organisme(
        Request $request,
        OrganismeRepository $repo
    )
    {
        $tree = new OrganismeTree($this->container, $request);
        $tree
            ->initialise($repo->findAll())
            ->setRoute('organismes');

        return $this->render('organisme/index.html.twig',             [
            'treeData' => $tree->getTree(),
            'items' => $tree->getItems(),
            'item' => $tree->getItem()
        ]);
    }
}
