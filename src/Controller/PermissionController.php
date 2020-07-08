<?php

namespace App\Controller;

use App\Dto\RubricDto;
use App\Dto\UnderRubricDto;
use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\RubricDtoRepository;
use App\Repository\RubricRepository;
use App\Repository\UnderRubricDtoRepository;
use App\Security\CurrentUser;
use App\Security\Role;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PermissionController extends AbstractController
{
    /**
     * @Route("/permission/{id}", name="permission")
     */
    public function permission(
        User $user,
        RubricRepository $repo,
        RubricDtoRepository $repoDto,
        UnderRubricDtoRepository $repoUDto
    )
    {
        return $this->render('user/permission.html.twig',
            $this->getPermission($user, $repo, $repoDto, $repoUDto));
    }

    public function getPermission(
        User $user,
        RubricRepository $repo,
        RubricDtoRepository $repoDto,
        UnderRubricDtoRepository $repoUDto)
    {
        $userDto = (new UserDto())->setId($user->getId());

        $dtoConsult = new RubricDto ();
        $dtoConsult->setVisible(RubricDto::TRUE);
        $dtoConsult->setUserDto($userDto);

        $dtoModif = new RubricDto ();
        $dtoModif->setForUpdate(RubricDto::TRUE);
        $dtoModif->setVisible(RubricDto::TRUE);
        $dtoModif->setUserDto($userDto);

        $dtouConsult = new UnderRubricDto ();
        $dtouConsult->setVisible(RubricDto::TRUE);
        $dtouConsult->setUserDto($userDto);

        $dtouModif = new UnderRubricDto ();
        $dtouModif->setForUpdate(RubricDto::TRUE);
        $dtouModif->setVisible(RubricDto::TRUE);
        $dtouModif->setUserDto($userDto);

        return [
            'item' => $user,
            'items' => $repo->findAllForAdmin(),
            'itemsConsult' => $repoDto->findAllForDto($dtoConsult, RubricDtoRepository::FILTRE_DTO_INIT_HOME),
            'itemsModif' => $repoDto->findAllForDto($dtoModif, RubricDtoRepository::FILTRE_DTO_INIT_HOME),
            'itemsConsultU' => $repoUDto->findAllForDto($dtouConsult, UnderRubricDtoRepository::FILTRE_DTO_INIT_HOME),
            'itemsModifU' => $repoUDto->findAllForDto($dtouModif, UnderRubricDtoRepository::FILTRE_DTO_INIT_HOME),
        ];
    }

    /**
     * @Route("/permission/", name="my_permission")
     */
    public function myPermission(
        CurrentUser $currentUser,
        RubricRepository $repo,
        RubricDtoRepository $repoDto,
        UnderRubricDtoRepository $repoUDto
    )
    {
        return $this->render('profil/permission.html.twig',
            $this->getPermission($currentUser->getUser(), $repo, $repoDto, $repoUDto));
    }

}
