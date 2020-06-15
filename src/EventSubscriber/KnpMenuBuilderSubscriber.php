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

        $menu->addChild('MainNavigationMenuItem', [
            'label' => 'MON LABEL',
            'childOptions' => $event->getChildOptions()
        ])->setAttribute('class', 'header');

        $menu->addChild('demande_personnelle_new', [
            'route' => 'home',
            'label' => 'Action sur ma route',
            'childOptions' => $event->getChildOptions()
        ])->setLabelAttribute('icon', 'fas fa-plus');

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $menu->addChild('administration', [
                'label' => 'ADMINISTRATION',
                'childOptions' => $event->getChildOptions()
            ])->setAttribute('class', 'header');

            $menu->addChild('admin_action', [
                'route' => 'home',
                'label' => 'Mon action admin',
                'childOptions' => $event->getChildOptions()
            ])->setLabelAttribute('icon', 'fas fa-cogs');
        }
    }
}
