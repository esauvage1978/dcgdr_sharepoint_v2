<?php

namespace App\Manager;

use App\Entity\Avatar;
use App\Entity\User;
use App\Helper\ToolCollecion;
use App\Repository\OrganismeRepository;
use App\Repository\UserRepository;
use App\Validator\UserValidator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{


    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var UserValidator
     */
    private $validator;

    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var OrganismeRepository
     */
    private $organismeRepository;

    public function __construct(
        EntityManagerInterface $manager,
        UserValidator $validator,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository,
        ParameterBagInterface $params,
        OrganismeRepository $organismeRepository
    )
    {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->organismeRepository = $organismeRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->params = $params;
    }

    public function save(User $user, $oldUserMail = null): bool
    {
        $this->initialise($user, $oldUserMail);

        if (!$this->validator->isValid($user)) {
            return false;
        }

        $this->manager->persist($user);
        $this->manager->flush();

        return true;
    }

    public function initialise(User $user, $oldUserMail = null)
    {
        $this->encodePassword($user);

        if (null === $user->getCreatedAt()) {
            $user->setCreatedAt(new \DateTime());
            $user->setisEnable(true);
            $user->setSubscription(false);
        } else {
            $user->setModifiedAt(new \DateTime());
        }


        if (!$user->getEmailValidatedToken() or
            ($user->getEmail() !== $oldUserMail and null !== $oldUserMail)) {
            $user
                ->setEmailValidated(false)
                ->setEmailValidatedToken(md5(random_bytes(50)));
        }

        $this->checkAvatar($user);

        if (!empty($user->getId())) {
            $this->setRelation(
                $user,
                $this->organismeRepository->findAllForUser($user->getId()),
                $user->getOrganismes()
            );
        }

        return true;
    }


    public function checkAvatar(User $user): bool
    {
        if (!file_exists($this->params->get('directory_avatar') .'/' . $user->getId() . '.png')) {
            copy(
                $this->params->get('directory_avatar') .'/__default.png',
                $this->params->get('directory_avatar') .'/' . $user->getId() . '.png'
            );
        }

        return true;
    }

    public function checkPassword($user, $pwd): bool
    {
        return $this->passwordEncoder->isPasswordValid($user, $pwd);
    }

    public function encodePassword(User $user): string
    {
        $plainPassword = $user->getPlainPassword();
        if ($plainPassword) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $plainPassword
                ));
        }

        return true;
    }

    public function getErrors(User $entity)
    {
        return $this->validator->getErrors($entity);
    }

    public function remove(User $entity)
    {
        $this->manager->remove($entity);
        $this->manager->flush();
    }

    public function validateEmail(User $user)
    {
        $user->setEmailValidated(true);
        $user->setEmailValidatedToken(date_format(new DateTime(), 'Y-m-d H:i:s'));
        $user->setRoles(['ROLE_USER']);

        return $this;
    }

    public function onConnected(User $user): bool
    {
        $user->setLoginAt(new DateTime());

        return true;
    }

    public function initialisePasswordForget(User $user): bool
    {
        $user->setForgetToken(md5(random_bytes(50)));

        return true;
    }

    public function initialisePasswordRecover(User $user, string $plainPassword, string $plainPasswordConfirmm): bool
    {
        $user->setForgetToken(date_format(new DateTime(), 'Y-m-d H:i:s'));
        $user->setPlainPassword($plainPassword);
        $user->setPlainPasswordConfirmation($plainPasswordConfirmm);

        return true;
    }

    public function initialisePasswordChange(User $user, string $plainPassword, string $plainPasswordConfirm): bool
    {
        $user->setPlainPassword($plainPassword);
        $user->setPlainPasswordConfirmation($plainPasswordConfirm);

        return true;
    }

    public function setRelation(User $user, $entitysOld, $entitysNew)
    {
        $em = new ToolCollecion($entitysOld, $entitysNew->toArray());

        foreach ($em->getDeleteDiff() as $entity) {
            $entity->removeUser($user);
        }

        foreach ($em->getInsertDiff() as $entity) {
            $entity->addUser($user);
        }
    }
}
