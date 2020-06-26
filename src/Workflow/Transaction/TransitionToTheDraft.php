<?php


namespace App\Workflow\Transaction;


class TransitionToTheDraft  extends TransitionAbstract
{

    public function getExplains(): array
    {
        return
            [
                'Vous pouvez passer ce porte-document à l\'état de brouillon.'
            ];
    }

    public function check()
    {
        $this->checkAll();
    }

}