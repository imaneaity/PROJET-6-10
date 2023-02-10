<?php

namespace App\Controller\Admin;

use App\Form\PizzaType;
use App\Repository\PizzaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PizzaController extends AbstractController
{
    /**
     * @Route("/admin/pizza/nouvelle", name="app_admin_pizza_create")
     */
    public function create(Request $request, PizzaRepository $repository): Response
    {

        //Création du formulaire
        $form = $this->createForm(PizzaType::class);

        $form->handleRequest($request);

        //tester si le formulaire est envoyé et est valide
        if($form->isSubmitted() && $form->isValid()){

            //sauvegarde de la pizza dans la bd
            $repository->add($form->getData(), true);

            //redirection vers la liste des pizzas
            return $this->redirectToRoute('app_admin_pizza_list');
        }

        return $this->render('admin/pizza/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/pizza", name="app_admin_pizza_list")
     */
    public function list(Request $request, PizzaRepository $repository): Response
    {
        $pizzas= $repository->findAll();
        //affichage en passant la liste des pizzas récupérées
        return $this->render('admin/pizza/list.html.twig', [
            'pizzas' => $pizzas,
        ]);
    }


}