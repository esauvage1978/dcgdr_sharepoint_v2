<?php

namespace App\Command;

use App\Command\AbstractCommand;
use App\Service\BackpackNotificator;
use App\Command\CommandInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NotificatorCommand extends AbstractCommand implements CommandInterface
{
    protected static $defaultName = 'app:notificator';

    private $backpackNotificator;

    public function __construct(
        BackpackNotificator $backpackNotificator
    ) {
        $this->backpackNotificator = $backpackNotificator;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Effectue les notifications.')
            ->setHelp('Cette commande permet de lancer toutes les notifications du site.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->runTraitement();

        $this->showMessage($output);

        return 0;
    }

    public function runTraitement(): void
    {
        $debut = microtime(true);

        $this->addMessage('Lancement des Notifications pour les porte-documents ');
        $this->addMessages($this->backpackNotificator->notifyNew());

        $fin = microtime(true);

        $this->addMessage('Traitement effectué en  '.$this->calculTime($fin, $debut).' millisecondes.');
    }
}
