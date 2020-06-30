<?php

namespace App\Twig;

use App\Entity\User;
use App\Repository\BackpackDtoRepository;
use App\Security\CurrentUser;
use App\Security\Role;
use App\Service\BackpackCounter;
use App\Service\BackpackMakerDto;
use App\Workflow\WorkflowData;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class BackpackExtension extends AbstractExtension
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var BackpackDtoRepository
     */
    protected $backpackDtoRepository;

    /**
     * @param CurrentUser $user
     */
    public function __construct( CurrentUser $user, BackpackDtoRepository $backpackDtoRepository)
    {
        $this->user = $user->getUser();
        $this->backpackDtoRepository=$backpackDtoRepository;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('sumBackpackPublishedForRubric', [$this, 'sumBackpackPublishedForRubric']),
            new TwigFilter('sumBackpackPublished', [$this, 'sumBackpackPublished']),
            new TwigFilter('hasNewForRubric', [$this, 'hasNewForRubric']),
            new TwigFilter('hasNewForUnderRubric', [$this, 'hasNewForUnderRubric']),
        ];
    }

    public function sumBackpackPublishedForRubric($rubricid)
    {
        $counter=new BackpackCounter(
            $this->backpackDtoRepository,
            $this->user);
        return $counter->get(BackpackMakerDto::PUBLISHED_FOR_RUBRIC,$rubricid);
    }

    public function hasNewForRubric($rubricid)
    {
        $counter=new BackpackCounter(
            $this->backpackDtoRepository,
            $this->user);
        return $counter->get(BackpackMakerDto::NEWS_FOR_RUBRIC,$rubricid);
    }

    public function sumBackpackPublished($underRubricID)
    {
        $counter=new BackpackCounter(
            $this->backpackDtoRepository,
            $this->user);
        return $counter->get(BackpackMakerDto::PUBLISHED_FOR_UNDERRUBRIC,$underRubricID);
    }

    public function hasNewForUnderRubric($underRubricid)
    {
        $counter=new BackpackCounter(
            $this->backpackDtoRepository,
            $this->user);
        return $counter->get(BackpackMakerDto::NEWS_FOR_UNDERRUBRIC,$underRubricid);
    }

}
