<?php

namespace App\Twig;

use App\Workflow\WorkflowData;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class BackpackExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('sumBackpackPublishedForRubric', [$this, 'sumBackpackPublishedForRubric']),
            new TwigFilter('sumBackpackPublished', [$this, 'sumBackpackPublished']),
            new TwigFilter('hasNewForRubric', [$this, 'hasNewForRubric']),
            new TwigFilter('hasNewForUnderRubric', [$this, 'hasNewForUnderRubric']),
        ];
    }

    public function sumBackpackPublishedForRubric($underRubrics = [])
    {
        $nbr = 0;
        foreach ($underRubrics as $underrubric) {
            foreach ($underrubric->getBackpacks() as $backpack) {
                if (
                    $underrubric->getIsEnable()
                    && $underrubric->getUnderThematic()->getIsEnable()
                    && $backpack->getcurrentState()===WorkflowData::STATE_PUBLISHED
                ) {
                    $nbr = $nbr + 1;
                }
            }
        }
        return $nbr;
    }

    public function hasNewForRubric($underRubrics = [])
    {
        $nbr = 0;
        foreach ($underRubrics as $underrubric) {
            foreach ($underrubric->getBackpacks() as $backpack) {
                if (
                    $underrubric->getEnable()
                    && $underrubric->getUnderThematic()->getEnable()
                    && $backpack->getEnable()
                    && !$backpack->getArchiving()
                ) {
                    if ($this->getNbrDayBeetwenDates(new \DateTime(), $backpack->getUpdateAt()) < 8) {
                        $nbr = $nbr + 1;
                    }
                }
            }
        }
        return $nbr;
    }

    private function getNbrDayBeetwenDates(\DateTime $date1, \DateTime $date2)
    {

        $nbJoursTimestamp = $date1->getTimestamp() - $date2->getTimestamp();

        return round($nbJoursTimestamp / 86400);
    }

    public function sumBackpackPublished($backpacks = [])
    {
        $nbr = 0;
        foreach ($backpacks as $backpack) {
            if (
                $backpack->getCurrentState()==WorkflowData::STATE_PUBLISHED
            ) {
                $nbr = $nbr + 1;
            }
        }
        return $nbr;
    }

    public function hasNewForUnderRubric($backpacks = [])
    {
        $nbr = 0;
        foreach ($backpacks as $backpack) {
            if (
                $backpack->getEnable()
                && !$backpack->getArchiving()
            ) {
                if ($this->getNbrDayBeetwenDates(new \DateTime(), $backpack->getUpdateAt()) < 8) {
                    $nbr = $nbr + 1;
                }
            }
        }
        return $nbr;
    }

}
