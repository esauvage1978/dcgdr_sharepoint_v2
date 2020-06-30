<?php


namespace App\Controller;

use App\Entity\Organisme;
use App\Form\Admin\OrganismeType;
use App\Manager\InterfaceManager;
use App\Manager\OrganismeManager;
use App\Repository\OrganismeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractGController extends AbstractController
{
    const SUCCESS = 'success';
    const DANGER = 'danger';
    const INFO = 'info';
    const WARNING = 'warning';

    CONST MSG_CREATE = 'La création est effectuée !';
    CONST MSG_CREATE_ERROR = 'Une erreur est survenue lors de la création, merci de corriger : ';
    CONST MSG_MODIFY = 'La modification est effectuée !';
    CONST MSG_MODIFY_ERROR = 'Une erreur est survenue lors de la modification, merci de corriger : ';
    CONST MSG_DELETE = 'La suppression est effectuée !';
    CONST MSG_DELETE_ERROR = 'Une erreur est intervenue, la suppression n\'a pas eu lieu !';

    CONST FORM='form';

    /**
     * @var Request
     */
    protected $request;
    protected $repository;
    /**
     * @var InterfaceManager
     */
    protected $manager;
    protected $domaine;


    public function listAction()
    {
        return $this->render($this->domaine . '/list.html.twig',
            [
                'items' => $this->repository->findAllForAdmin()
            ]);
    }

    public function deleteAction(
        Request $request,
        $item
    )
    {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $this->addFlash(self::SUCCESS, self::MSG_DELETE);
            $this->manager->remove($item);
        }
        return $this->redirectToRoute($this->domaine . '_list');
    }

    public function showAction(
        Request $request,
        $item
    )
    {
        return $this->render($this->domaine . '/show.html.twig', [
            'item' => $item
        ]);
    }

    public function editAction(
        Request $request,
        $item,
        $class,
        $edit = true
    ){
        $form = $this->createForm($class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($item)) {
                $this->addFlash(self::SUCCESS, self::MSG_MODIFY);
                return $this->redirectToRoute($this->domaine . '_edit', ['id' => $item->getId()]);
            }
            $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($item));
        }

        return $this->render($this->domaine . '/' . ($edit?'edit':'add') .'.html.twig', [
            'item' => $item,
            'form' => $form->createView()
        ]);
    }
}