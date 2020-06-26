<?php


namespace App\Workflow\Transaction;


class TransitionToArchive  extends TransitionAbstract
{

    public function getExplains(): array
    {
        return
            [
                'Vous pouvez archiver ce porte-document.'
            ];
    }

    public function check()
    {
        $this->checkAll();
    }

}