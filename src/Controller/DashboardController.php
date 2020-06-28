<?php

namespace App\Controller;

use App\Dto\BackpackDto;
use App\Dto\RubricDto;
use App\Dto\ThematicDto;
use App\Dto\UnderRubricDto;
use App\Dto\UnderThematicDto;
use App\Dto\UserDto;
use App\Repository\BackpackDtoRepository;
use App\Service\MakeDashboard;
use App\Workflow\WorkflowData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(
        BackpackDtoRepository $backpackDtoRepository
    ) {
        $md=new MakeDashboard($backpackDtoRepository,$this->getUser(), $this->isgranted('ROLE_GESTIONNAIRE'));

        $options = [
            $md->getDraft(),
            $md->getPublished(),
            $md->getArchived(),
            $md->getAbandonned(),
            $md->getHide()
        ];

        return $this->render('dashboard/index.html.twig',
            ['options' => $options]
        );
    }
}
