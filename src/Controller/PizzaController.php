<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Pizza;
use App\Form\CommentType;
use App\Form\PizzaType;
use App\Repository\PizzaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PizzaController extends AbstractController
{
    #[Route('/pizzas', name: 'app_pizzas')]
    public function index(PizzaRepository $pizzaRepository): Response
    {
        $pizzas = $pizzaRepository->findAll();

        return $this->render('pizza/index.html.twig', [

            'pizzas' => $pizzas,
        ]);
    }

    #[Route('/pizza{id}', name: 'app_pizza')]
    public function show(Pizza $pizza):Response
    {

        $comment = new Comment();
        $form = $this->createForm(CommentType::class,$comment);



        return $this->render('pizza/show.html.twig',[
            "pizza"=> $pizza,
            "form"=>$form
        ]);

    }

    #[Route('/create', name: 'app_create')]
    public function create(Request $request, EntityManagerInterface $manager):Response
    {

        $pizza = new Pizza();
        $form =  $this->createForm(PizzaType::class, $pizza);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($pizza);

            $manager->flush();

            return $this->redirectToRoute('app_pizzas', ["id"=>$pizza->getId()]);
        }

        return $this->render('pizza/create.html.twig',[
            'formulaire'=>$form->createView()
        ]);
    }

    #[Route('/pizza/delete/{id}', name:'app_delete')]
    public function delete(Pizza $pizza, EntityManagerInterface $manager):Response
    {
        $manager->remove($pizza);
        $manager->flush();

        return $this->redirectToRoute("app_pizzas");
    }

    #[Route('/edit/{id}',name: 'app_edit')]
    public function edit(Request $request,EntityManagerInterface $manager,Pizza $pizza):Response
    {

        $formulaire = $this->createForm(PizzaType::class,$pizza);
        $formulaire->handleRequest($request);
        if($formulaire->isSubmitted() && $formulaire->isValid())
        {
            $manager->persist($pizza);

            $manager->flush();

            return $this->redirectToRoute('app_pizza', ["id"=>$pizza->getId()]);
        }


        return $this->render("pizza/create.html.twig",[
            "formulaire"=>$formulaire->createView()
        ]);
    }

}
