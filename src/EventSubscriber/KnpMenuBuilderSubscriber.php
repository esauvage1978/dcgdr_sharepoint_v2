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

        $menu->addChild('Administration', [
            'route' => 'admin',
            'label' => 'Administration',
            'childOptions' => $event->getChildOptions()
        ])->setLabelAttribute('icon', 'fas fa-cog');

        $menu->getChild('Administration')->addChild('Organisme', [
            'route' => 'organisme_list',
            'label' => 'Organisme',
            'childOptions' => $event->getChildOptions()
        ])->setLabelAttribute('icon', 'fas fa-building');

        $menu->addChild('documentation', [
            'route' => 'documentation',
            'label' => 'Consulter',
            'childOptions' => $event->getChildOptions()
        ])->setLabelAttribute('icon', 'fas fa-file-pdf');
    }
}
