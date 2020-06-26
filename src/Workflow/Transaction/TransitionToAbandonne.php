<?php


namespace App\Workflow\Transaction;


class TransitionToAbandonne extends TransitionAbstract
{
    public function getExplains(): array
    {
        return ['Vous pouvez abandonner ce porte-document.'];
    }
}