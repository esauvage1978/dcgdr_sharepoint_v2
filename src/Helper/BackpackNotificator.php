<?php

namespace App\Helper;

use App\Dto\BackpackDto;
use App\Dto\UserDto;
use App\Entity\Backpack;
use App\Mail\BackpackMail;
use App\Repository\BackpackDtoRepository;
use App\Repository\UserRepository;
use App\Workflow\WorkflowData;

class BackpackNotificator extends Messagor
{
    /**
     * @var mixed
     */
    private $usersSubscription;

    /**
     * @var BackpackDto
     */
    private $backpackDto;

    /**
     * @var BackpackDtoRepository
     */
    private $backpackRepository;

    /**
     * @var BackpackMail
     */
    private $backpackMail;


    public function __construct(
        UserRepository $userRepository,
        BackpackDto $backpackDto,
        backpackDtoRepository $backpackRepository,
        BackpackMail $backpackMail
    )
    {
        $this->usersSubscription = $userRepository->findAllUserSubscription();
        $this->backpackDto = $backpackDto;
        $this->backpackRepository = $backpackRepository;
        $this->backpackMail = $backpackMail;

        parent::__construct();
    }

    public function notifyNew()
    {
        $this->addMessage('Lancement des notifications pour ' . count($this->usersSubscription) . ' utilisateurs :');
        $this->notifyBackpackNew($this->usersSubscription);

        return $this->getMessages();
    }

    private function notifyBackpackNew(array $users)
    {
        foreach ($users as $user) {
            $this->backpackDto
                ->setCurrentState(WorkflowData::STATE_PUBLISHED)
                ->setIsNew(BackpackDto::TRUE)
                ->setUserDto((new UserDto())->setId($user->getId()))
                ->setVisible(BackpackDto::TRUE);


            /** @var Backpack[] $result */
            $result = $this->backpackRepository->findAllForDto($this->backpackDto, BackpackDtoRepository::FILTRE_DTO_INIT_HOME);

            if (empty($result)) {
                $this->addMessage($user->getName() . ' -> pas de nouveauté');
                continue;
            }

            $this->addMessage(
                Messagor::TABULTATION .
                ' Notification à ' . $user->getName() .
                ' [' . $user->getEmail() . ']' . ' -> ' . count($result) . ' nouveautés');

            /*$this->backpackMail->send(
                $user,
                BackpackMail::NEW,
                'Liste des dernières notifications',
                ['backpacks' => $result]);
*/

        }
    }
}
