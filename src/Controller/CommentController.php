<?php

namespace App\Controller;

use App\Entity\Comment;

use App\Entity\Pizza;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentController extends AbstractController
{
    #[Route('/comment/create/{id}', name: 'app_comment_create')]
    public function create(Request $request, EntityManagerInterface $manager, Pizza $pizza):Response
    {
        $comment= new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $comment->setPizza($pizza);
            $manager->persist($comment);
            $manager->flush();
        }

        return $this->redirectToRoute("app_pizza",["id"=>$pizza->getId()]);
    }


    #[Route('/comment/delete/{id}', name:'app_comment_delete')]
    public function delete(Comment $comment, EntityManagerInterface $manager):Response
    {
        $pizza = $comment->getPizza();
        $manager->remove($comment);
        $manager->flush();

        return $this->redirectToRoute("app_pizza", ["id"=>$pizza->getId()]);
    }

    #[Route('/comment/edit/{id}', name: 'app_comment_edit')]
    public function edit(Request $request, EntityManagerInterface $manager, Comment $comment):Response
    {
        $pizza = $comment->getPizza();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute("app_pizza", ["id" => $pizza->getId()]);
        }


        return $this->render('comment/edit.html.twig', [
            'controller_name' => 'PizzaController',
            "form" => $form->createView(),
        ]);
    }
}
