<?php

namespace App\Controller;

use App\Dto\RubricDto;
use App\Dto\UserDto;
use App\Entity\Backpack;
use App\Entity\Comment;
use App\Form\Comment\CommentFormType;
use App\Helper\ParamsInServices;
use App\Manager\CommentManager;
use App\Manager\ThematicManager;
use App\Repository\BackpackDtoRepository;
use App\Repository\CommentRepository;
use App\Repository\RubricDtoRepository;
use App\Repository\ThematicRepository;
use App\Security\CurrentUser;
use App\Service\BackpackMakerDto;
use App\Tree\BackpackTree;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractGController
{
    public function __construct
    (
        CommentRepository $repository,
        CommentManager $manager
    )
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'thematic';
    }
    /**
     * @Route("/{id}/comment", name="comment")
     * @IsGranted("ROLE_USER")
     */
    public function add(Backpack $item,Request $request)
    {
        $form = $this->createForm(CommentFormType::class, ['data'=>$item->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment=$this->manager->initialiseForm($form->getData());
            $comment->setBackpack($item);

            if ($this->manager->save($comment)) {
                $this->addFlash(self::SUCCESS, self::MSG_MODIFY);

                return $this->redirectToRoute( 'underrubric_show',
                    [
                        'id' => $item->getUnderRubric()->getId(),
                        'visible'=>'true',
                        'idItem'=>$item->getId()
                    ]);
            }
            $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($comment));
        }

        return $this->render('backpack/comment_add.html.twig', [
            'item' => $item,
            'form' => $form->createView()
        ]);
    }

    /**
     * @return Response
     */
    public function searchFormAction(): Response
    {
        return $this->render('home/search-form.html.twig', []);
    }
    /**
     * @Route("/{id}/comments", name="comments")
     * @IsGranted("ROLE_USER")
     */
    public function list(Backpack $item)
    {
        return $this->render('backpack/comments.html.twig', [
            'item' => $item
        ]);
    }


}
