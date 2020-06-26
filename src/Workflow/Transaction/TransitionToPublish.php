<?php


namespace App\Workflow\Transaction;


class TransitionToPublish  extends TransitionAbstract
{

    public function getExplains(): array
    {
        return
            [
                'Vous pouvez publier ce porte-document.',
                'La liste des porte-documents publiée cette semaine sera envoyée aux personnes abonnées'
            ];
    }

    public function check()
    {
        $this->checkAll();
    }

}