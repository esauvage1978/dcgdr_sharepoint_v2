<?php
namespace App\EventSubscriber;

use App\Workflow\WorkflowData;
use KevinPapst\AdminLTEBundle\Event\KnpMenuEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class KnpMenuBuilderSubscriber implements EventSubscriberInterface
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KnpMenuEvent::class => ['onSetupMenu', 100],
        ];
    }

    public function onSetupMenu(KnpMenuEvent $event)
    {
        $menu = $event->getMenu();
        $menu->addChild('home', [
            'route' => 'home',
            'label' => 'Page d\'accueil',
            'childOptions' => $event->getChildOptions()
        ])->setLabelAttribute('icon', 'fas fa-home');

        $menu->addChild('dashboard', [
            'route' => 'dashboard',
            'label' => 'Tableau de bord',
            'childOptions' => $event->getChildOptions()
        ])->setLabelAttribute('icon', 'fas fa-tachometer-alt');

        $menu->addChild(
            'backpack',
            [
                'route'=>'home',
                'label' => 'Porte-documents',
                'childOptions' => $event->getChildOptions(),
                'options' => ['branch_class' => 'treeview']]
        )->setLabelAttribute('icon', 'nav-icon fas fa-suitcase');

        $menu->getChild('backpack')->addChild(
            'backpack-add',
            [
                'route' => 'backpack_add',
                'label' => 'Création',
                'childOptions' => $event->getChildOptions()]
        )->setLabelAttribute('icon', 'fas fa-plus-circle');

        $menu->getChild('backpack')->addChild(
            'backpack-'. WorkflowData::STATE_DRAFT,
            [
                'route' => 'backpacks_show_draft',
                'label' => WorkflowData::getNameOfState(WorkflowData::STATE_DRAFT),
                'childOptions' => $event->getChildOptions()]
        )->setLabelAttribute('icon', 'far fa-arrow-alt-circle-down text-secondary');



        $menu->getChild('backpack')->addChild(
            'backpack-'. WorkflowData::STATE_ARCHIVED,
            [
                'route' => 'backpacks_show_archived',
                'label' => WorkflowData::getNameOfState(WorkflowData::STATE_ARCHIVED),
                'childOptions' => $event->getChildOptions()]
        )->setLabelAttribute('icon', 'far fa-arrow-alt-circle-down text-warning');

        $menu->getChild('backpack')->addChild(
            'backpack-'. WorkflowData::STATE_ABANDONNED,
            [
                'route' => 'backpacks_show_abandonned',
                'label' => WorkflowData::getNameOfState(WorkflowData::STATE_ABANDONNED),
                'childOptions' => $event->getChildOptions()]
        )->setLabelAttribute('icon', 'far fa-arrow-alt-circle-down text-danger');

        if ($this->security->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $menu->addChild('profil', [
                'route' => 'profil',
                'label' => 'Votre compte',
                'childOptions' => $event->getChildOptions()
            ])->setLabelAttribute('icon', 'fas fa-user');
        }
        $menu->addChild('admin', [
            'route' => 'admin',
            'label' => 'Administration',
            'childOptions' => $event->getChildOptions()
        ])->setLabelAttribute('icon', 'fas fa-cog');

        $menu->addChild('documentation', [
            'route' => 'documentation',
            'label' => 'Documentation',
            'childOptions' => $event->getChildOptions()
        ])->setLabelAttribute('icon', 'fas fa-file-pdf');

        if ($this->security->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $menu->addChild(
                'logout',
                ['route' => 'user_logout', 'label' => 'Déconnexion', 'childOptions' => $event->getChildOptions()]
            )->setLabelAttribute('icon', 'fas fa-sign-out-alt');
        } else {
            $menu->addChild(
                'login',
                ['route' => 'user_login', 'label' => 'Connexion', 'childOptions' => $event->getChildOptions()]
            )->setLabelAttribute('icon', 'fas fa-sign-in-alt');
        }
    }
}
