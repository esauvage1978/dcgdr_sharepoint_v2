<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Admin\UserType;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @route("/user")
 */
class UserController extends AbstractGController
{
    const DOMAINE = 'user';

    public function __construct
    (
        UserRepository $repository,
        UserManager $manager
    )
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'user';
    }

    /**
     * @Route("/", name="user_list", methods={"GET"})
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/add", name="user_add", methods={"GET","POST"})
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new User(), UserType::class,false);
    }

    /**
     * @Route("/{id}", name="user_del", methods={"DELETE"})
     */
    public function delete(Request $request, User $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(Request $request, User $item)
    {
        return $this->showAction($request, $item);
    }


    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $item)
    {
        return $this->editAction($request, $item, UserType::class);
    }
}
