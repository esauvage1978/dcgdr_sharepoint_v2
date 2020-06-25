<?php

/*
 * This file is part of the AdminLTE-Bundle demo.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use App\Dto\BackpackDto;
use App\Dto\RubricDto;
use App\Dto\ThematicDto;
use App\Dto\UnderRubricDto;
use App\Dto\UnderThematicDto;
use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\BackpackDtoRepository;
use App\Workflow\WorkflowData;
use KevinPapst\AdminLTEBundle\Event\NotificationListEvent;
use KevinPapst\AdminLTEBundle\Helper\Constants;
use KevinPapst\AdminLTEBundle\Model\NotificationModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class NotificationSubscriber adds notification messages to the top bar.
 */
class NotificationSubscriber implements EventSubscriberInterface
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $auth;
    /**
     * @var Security
     */
    private $security;

    /**
     * @var BackpackDtoRepository
     */
    private $backpackDtoRepository;
    /**
     * @param AuthorizationCheckerInterface $auth
     */
    public function __construct(
        AuthorizationCheckerInterface $auth,
        Security $security,
        BackpackDtoRepository $dto
        ){
        $this->auth = $auth;
        $this->security=$security;
        $this->backpackDtoRepository=$dto;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            NotificationListEvent::class => ['onNotifications', 100],
        ];
    }

    /**
     * @param NotificationListEvent $event
     */
    public function onNotifications(NotificationListEvent $event)
    {

        if (!$this->auth->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $notification = new NotificationModel('Vous n\'êtes pas connecté !', Constants::TYPE_ERROR, 'fas fa-key');
            $notification->setId(1);
            $event->addNotification($notification);
            return;
        }

        if (!$this->security->getUser()->getEmailValidated()) {
            $notification = new NotificationModel('Adresse mail non vérifiée !', Constants::TYPE_ERROR, 'fas fa-envelope');
            $notification->setId(2);
            $event->addNotification($notification);

        }

        $dto=new BackpackDto();

        $dto
            ->setCurrentPlace(WorkflowData::STATE_DRAFT)
            ->setThematicDto((new ThematicDto())->setIsEnable(RubricDto::TRUE))
            ->setUnderThematicDto((new UnderThematicDto())->setIsEnable(RubricDto::TRUE))
            ->setUnderRubricDto((new UnderRubricDto())->setIsEnable(RubricDto::TRUE))
            ->setRubricDto((new RubricDto())->setIsEnable(RubricDto::TRUE));

        if (!$this->auth->isGranted('GESTIONNAIRE')) {
            $nbr=$this->backpackDtoRepository->countForDto($dto);
            if($nbr!="0") {
                $notification = new NotificationModel($nbr . ' brouillon'. ($nbr=="1"?'':'s') , Constants::TYPE_WARNING, 'fas fa-suitcase');
                $notification->setId(3);
                $event->addNotification($notification);
            }
        }

        $dto->setOwnerDto((new UserDto())->setId($this->security->getUser()->getId()));
        $dto->setUserDto((new UserDto())->setId($this->security->getUser()->getId()));
        $nbr=$this->backpackDtoRepository->countForDto($dto);
        if($nbr!="0") {
            $notification = new NotificationModel(($nbr=="1"?'Votre ':'vos '. $nbr)  . ' brouillon'. ($nbr=="1"?'':'s') , Constants::TYPE_WARNING, 'fas fa-suitcase');
            $notification->setId(4);
            $event->addNotification($notification);
        }
    }
}
