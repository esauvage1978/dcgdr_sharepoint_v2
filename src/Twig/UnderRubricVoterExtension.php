<?php

namespace App\Twig;

use App\Entity\Rubric;
use App\Entity\UnderRubric;
use App\Entity\User;
use App\Security\CurrentUser;
use App\Security\RubricVoter;
use App\Security\UnderRubricVoter;
use Doctrine\Common\Collections\Collection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Symfony\Component\Security\Core\Security;

class UnderRubricVoterExtension extends AbstractExtension
{
    /**
     * @var UnderRubricVoter
     */
    private $underrubricVoter;

    /**
     * @var User|null
     */
    private $user;

    public function __construct(
        CurrentUser $currentUser,
        UnderRubricVoter $underrubricVoter
    ) {
        $this->user = $currentUser->getUser();
        $this->underrubricVoter=$underrubricVoter;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('underrubricCanRead', [$this, 'underrubricCanRead']),
            new TwigFilter('underrubricCanUpdate', [$this, 'underrubricCanUpdate']),
        ];
    }

    public function underrubricCanRead(UnderRubric $underrubric)
    {
        return $this->underrubricVoter->canRead($underrubric, $this->user);
    }

    public function underrubricCanUpdate(UnderRubric $underrubric)
    {
        return $this->underrubricVoter->canUpdate($underrubric, $this->user);
    }
}
