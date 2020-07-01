<?php

namespace App\EventSubscriber;

use App\Security\CurrentUser;
use App\Security\Role;
use App\Workflow\WorkflowData;
use KevinPapst\AdminLTEBundle\Event\KnpMenuEvent;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class KnpMenuBuilderSubscriber implements EventSubscriberInterface
{
    /**
     * @var CurrentUser
     */
    private $currentUser;

    /**
     * @var ItemInterface
     */
    private $menu;

    /**
     * @var KnpMenuEvent
     */
    private $event;

    public function __construct(CurrentUser $currentUser)
    {
        $this->currentUser = $currentUser;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KnpMenuEvent::class => ['onSetupMenu', 100],
        ];
    }

    public function onSetupMenu(KnpMenuEvent $event)
    {
        $this->event = $event;
        $this->menu = $this->event->getMenu();

        if ($this->currentUser->isAuthenticatedRemember()) {
            $this->addHome();
            $this->addDashboard();
            $this->addBackpack();
            $this->addProfil();
            $this->addAdmin();
            $this->addDoc();
            $this->addDeconnexion();
        } else {
            $this->addHome();
            $this->addDoc();
            $this->addConnexion();
        }
    }

    private function addAdmin()
    {
        if(Role::isAdmin($this->currentUser->getUser())) {
            $this->menu->addChild('admin', [
                'route' => 'admin',
                'label' => 'Administration',
                'childOptions' => $this->event->getChildOptions()
            ])->setLabelAttribute('icon', 'fas fa-wrench');
        }
    }

    private function addBackpack()
    {
        $this->menu->addChild(
            'backpack',
            [
                'route' => 'home',
                'label' => 'Porte-documents',
                'childOptions' => $this->event->getChildOptions(),
                'options' => ['branch_class' => 'treeview']]
        )->setLabelAttribute('icon', 'nav-icon fas fa-suitcase');

        $this->menu->getChild('backpack')->addChild(
            'backpack-add',
            [
                'route' => 'backpack_add',
                'label' => 'Création',
                'childOptions' => $this->event->getChildOptions()]
        )->setLabelAttribute('icon', 'fas fa-plus-circle');

        $this->menu->getChild('backpack')->addChild(
            'backpack-' . WorkflowData::STATE_DRAFT,
            [
                'route' => 'backpacks_draft',
                'label' => WorkflowData::getNameOfState(WorkflowData::STATE_DRAFT),
                'childOptions' => $this->event->getChildOptions()]
        )->setLabelAttribute('icon', 'far fa-arrow-alt-circle-down text-info');

        $this->menu->getChild('backpack')->addChild(
            'backpack-' . WorkflowData::STATE_ARCHIVED,
            [
                'route' => 'backpacks_archived',
                'label' => WorkflowData::getNameOfState(WorkflowData::STATE_ARCHIVED),
                'childOptions' => $this->event->getChildOptions()]
        )->setLabelAttribute('icon', 'far fa-arrow-alt-circle-down text-warning');

        $this->menu->getChild('backpack')->addChild(
            'backpack-' . WorkflowData::STATE_ABANDONNED,
            [
                'route' => 'backpacks_abandonned',
                'label' => WorkflowData::getNameOfState(WorkflowData::STATE_ABANDONNED),
                'childOptions' => $this->event->getChildOptions()]
        )->setLabelAttribute('icon', 'far fa-arrow-alt-circle-down text-danger');
    }

    private function addDashboard()
    {
        $this->menu->addChild('dashboard', [
            'route' => 'dashboard',
            'label' => 'Tableau de bord',
            'childOptions' => $this->event->getChildOptions()
        ])->setLabelAttribute('icon', 'fas fa-tachometer-alt');
    }

    private function addDeconnexion()
    {
        $this->menu->addChild(
            'logout',
            ['route' => 'user_logout', 'label' => 'Déconnexion', 'childOptions' => $this->event->getChildOptions()]
        )->setLabelAttribute('icon', 'fas fa-sign-out-alt');
    }

    private function addDoc()
    {
        $this->menu->addChild('documentation', [
            'route' => 'documentation',
            'label' => 'Documentation',
            'childOptions' => $this->event->getChildOptions()
        ])->setLabelAttribute('icon', 'fas fa-file-pdf');
    }

    private function addConnexion()
    {
        $this->menu->addChild(
            'login',
            ['route' => 'user_login', 'label' => 'Connexion', 'childOptions' => $this->event->getChildOptions()]
        )->setLabelAttribute('icon', 'fas fa-sign-in-alt');
    }

    private function addHome()
    {
        $this->menu->addChild('home', [
            'route' => 'home',
            'label' => 'Page d\'accueil',
            'childOptions' => $this->event->getChildOptions()
        ])->setLabelAttribute('icon', 'fas fa-home');
    }

    private function addProfil()
    {
        $this->menu->addChild('profil', [
            'route' => 'profil',
            'label' => 'Votre compte',
            'childOptions' => $this->event->getChildOptions()
        ])->setLabelAttribute('icon', 'fas fa-user');
    }

}
