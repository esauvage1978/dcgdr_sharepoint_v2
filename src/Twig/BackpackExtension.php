<?php

namespace App\Twig;

use App\Entity\Backpack;
use App\Entity\Rubric;
use App\Entity\User;
use App\Repository\BackpackDtoRepository;
use App\Security\BackpackVoter;
use App\Security\CurrentUser;
use App\Service\BackpackCounter;
use App\Service\BackpackMakerDto;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class BackpackExtension extends AbstractExtension
{
    /**
     * @var BackpackVoter
     */
    private $backpackVoter;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var BackpackDtoRepository
     */
    protected $backpackDtoRepository;

    /**
     * BackpackExtension constructor.
     * @param CurrentUser $user
     * @param BackpackDtoRepository $backpackDtoRepository
     * @param BackpackVoter $backpackVoter
     */
    public function __construct(
        CurrentUser $user,
        BackpackDtoRepository $backpackDtoRepository,
        BackpackVoter $backpackVoter) {
        $this->user = $user->getUser();
        $this->backpackDtoRepository = $backpackDtoRepository;
        $this->backpackVoter=$backpackVoter;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('sumBackpackPublishedForRubric', [$this, 'sumBackpackPublishedForRubric']),
            new TwigFilter('sumBackpackPublished', [$this, 'sumBackpackPublished']),
            new TwigFilter('hasNewForRubric', [$this, 'hasNewForRubric']),
            new TwigFilter('hasNewForUnderRubric', [$this, 'hasNewForUnderRubric']),
            new TwigFilter('backpackCanRead', [$this, 'backpackCanRead']),
            new TwigFilter('backpackCanUpdate', [$this, 'backpackCanUpdate']),
            new TwigFilter('backpackCanDelete', [$this, 'backpackCanDelete']),
        ];
    }

    public function sumBackpackPublishedForRubric($rubricid)
    {
        $counter = new BackpackCounter(
            $this->backpackDtoRepository,
            $this->user);
        return $counter->get(BackpackMakerDto::PUBLISHED_FOR_RUBRIC, $rubricid);
    }

    public function hasNewForRubric($rubricid)
    {
        $counter = new BackpackCounter(
            $this->backpackDtoRepository,
            $this->user);
        return $counter->get(BackpackMakerDto::NEWS_FOR_RUBRIC, $rubricid);
    }

    public function sumBackpackPublished($underRubricID)
    {
        $counter = new BackpackCounter(
            $this->backpackDtoRepository,
            $this->user);
        return $counter->get(BackpackMakerDto::PUBLISHED_FOR_UNDERRUBRIC, $underRubricID);
    }

    public function hasNewForUnderRubric($underRubricid)
    {
        $counter = new BackpackCounter(
            $this->backpackDtoRepository,
            $this->user);
        return $counter->get(BackpackMakerDto::NEWS_FOR_UNDERRUBRIC, $underRubricid);
    }

    public function backpackCanRead(Backpack $item)
    {
        return $this->backpackVoter->canRead($item, $this->user);
    }

    public function backpackCanUpdate(Backpack $item)
    {
        return $this->backpackVoter->canUpdate($item, $this->user);
    }

    public function backpackCanDelete(Backpack $item)
    {
        return $this->backpackVoter->canDelete($item, $this->user);
    }
}
