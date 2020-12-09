<?php

namespace App\Controller;

use App\Service\MakeDashboard;
use App\Service\BackpackMakerDto;
use App\Repository\BackpackDtoRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     * @IsGranted("ROLE_USER")
     */
    public function index(BackpackDtoRepository $backpackDtoRepository)
    {
        $md = new MakeDashboard($backpackDtoRepository, $this->getUser());

        $draft = [
            $md->getData(BackpackMakerDto::DRAFT),
            $md->getData(BackpackMakerDto::MY_DRAFT_UPDATABLE),
        ];

        $abandonned = [
            $md->getData(BackpackMakerDto::ABANDONNED),
            $md->getData(BackpackMakerDto::MY_ABANDONNED_UPDATABLE),
        ];


        $published = [
            $md->getData(BackpackMakerDto::PUBLISHED),
            $md->getData(BackpackMakerDto::MY_PUBLISHED_UPDATABLE),
        ];

        $archived = [
            $md->getData(BackpackMakerDto::ARCHIVED),
            $md->getData(BackpackMakerDto::MY_ARCHIVED_UPDATABLE),
        ];

        return $this->render(
            'dashboard/index.html.twig',
            [
                'draft' => $draft,
                'abandonned' => $abandonned,
                'published' => $published,
                'archived' => $archived,
            ]
        );
    }
}
