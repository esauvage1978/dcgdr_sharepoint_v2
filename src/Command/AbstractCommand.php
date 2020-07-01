<?php

namespace App\Command;

use App\Helper\StackMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{

    /**
     * @var StackMessage
     */
    private $stackMessage;


    protected function calculTime($fin, $debut): int
    {
        return ($fin - $debut) * 1000;
    }

    public function showMessage(OutputInterface $output)
    {
        foreach ($this->stackMessage->toArray() as $msg) {
            $output->writeln($msg);
        }
    }

    public function addMessage(string $info)
    {
        $this->stackMessage->push($info);
    }

    public function addMessages(array $infos)
    {
        $this->stackMessage->pushs($infos);
    }

    public function getMessagesForAlert(): string
    {
        $affichage = "";
        foreach ($this->stackMessage->toArray() as $msg) {
            $affichage .= '<br/>' . $msg;
        }
        return $affichage;
    }

    public function getMessages(): array
    {
        return $this->stackMessage->toArray();
    }

    public function __construct()
    {
        $this->stackMessage = new StackMessage();
        parent::__construct();
    }
}
