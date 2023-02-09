<?php

namespace App\Controller;

use App\Repository\PizzaRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PizzaController extends AbstractController
{
    /**
     * @Route("/", name="app_pizza_home")
     */
    public function home(PizzaRepository $repository): Response
    {
        //recuperer la liste de toutes les pizzas
        $pizzas= $repository->findAll();


        return $this->render('pizza/home.html.twig', [
            'pizzas' => $pizzas,
        ]);
    }



    
}
