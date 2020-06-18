<?php
namespace App\EventSubscriber;

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
