<?php

namespace App\Twig;

use App\Entity\Rubric;
use App\Entity\User;
use App\Security\CurrentUser;
use App\Security\RubricVoter;
use Doctrine\Common\Collections\Collection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Symfony\Component\Security\Core\Security;

class RubricVoterExtension extends AbstractExtension
{
    /**
     * @var RubricVoter
     */
    private $rubricVoter;

    /**
     * @var User|null
     */
    private $user;

    public function __construct(
        CurrentUser $currentUser,
        RubricVoter $rubricVoter
    ) {
        $this->user = $currentUser->getUser();
        $this->rubricVoter=$rubricVoter;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('rubricCanRead', [$this, 'rubricCanRead']),
            new TwigFilter('rubricCanUpdate', [$this, 'rubricCanUpdate']),
        ];
    }

    public function rubricCanRead(Rubric $rubric)
    {
        return $this->rubricVoter->canRead($rubric, $this->user);
    }

    public function rubricCanUpdate(Rubric $rubric)
    {
        return $this->rubricVoter->canUpdate($rubric, $this->user);
    }
}
