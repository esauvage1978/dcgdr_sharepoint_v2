<?php


namespace App\Twig;


use App\Entity\Action;
use App\Entity\Backpack;
use App\Workflow\WorkflowData;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class WorkflowExtension extends AbstractExtension
{
    public function __construct()
    {
    }

    public function getFilters()
    {
        return [
            new TwigFilter('workflowGetNameOfState', [$this, 'workflowGetNameOfState']),
            new TwigFilter('workflowGetShortNameOfState', [$this, 'workflowGetShortNameOfState']),
            new TwigFilter('workflowGetForeColorOfState', [$this, 'workflowGetForeColorOfState']),
            new TwigFilter('workflowGetBGColorOfState', [$this, 'workflowGetBGColorOfState']),
            new TwigFilter('workflowGetModalDataForTransition', [$this, 'workflowGetModalDataForTransition']),
            new TwigFilter('workflowGetTransitionsForState', [$this, 'workflowGetTransitionsForState']),
            new TwigFilter('workflowGetExplains', [$this, 'workflowGetExplains']),
            new TwigFilter('workflowGetCheckMessages', [$this, 'workflowGetCheckMessages']),
            new TwigFilter('workflowGetIconOfState', [$this, 'workflowGetIconOfState']),
        ];
    }


    public function workflowGetNameOfState(string $state)
    {
        return WorkflowData::getNameOfState($state);
    }

    public function workflowGetShortNameOfState(string $state)
    {
        return WorkflowData::getShortNameOfState($state);
    }
    public function workflowGetBGColorOfState(string $state)
    {
        return WorkflowData::getBGColorOfState($state);
    }
    public function workflowGetForeColorOfState(string $state)
    {
        return WorkflowData::getForeColorOfState($state);
    }

    public function workflowGetIconOfState(string $state)
    {
        return WorkflowData::getIconOfState($state);
    }

    public function workflowGetTransitionsForState( string $state)
    {
        return WorkflowData::getTransitionsForState( $state);
    }

    public function workflowGetModalDataForTransition(string $transition)
    {
        return WorkflowData::getModalDataForTransition($transition);
    }

    public function workflowGetExplains(Backpack $backpack, string $transition)
    {
        $object =  'App\Workflow\Transaction\Transition' . ucfirst($transition);
        $instance = new $object($backpack);
        return $instance->getExplains();
    }


    public function workflowGetCheckMessages(Backpack $backpack, string $transition)
    {
        $object =  'App\Workflow\Transaction\Transition' . ucfirst($transition);
        $instance = new $object($backpack);
        return $instance->getCheckMessages();
    }

}