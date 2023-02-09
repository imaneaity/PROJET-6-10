<?php

namespace App\Controller;

use App\DTO\Payment;
use App\Entity\Order;
use App\Form\PaymentType;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    /**
     * @Route("/commander", name="app_order_display")
     */
    public function display(Request $request, OrderRepository $repository): Response
    {
        //recuperer l'utilisateur
        $user = $this->getUser();

        //initialiser le paiement
        $payment= new Payment();

        //récupere l'addresse de l'utilisateur our la commande
        $payment->address = $user->getAddress(); //sert à préremplir le formulaire avec l'addresse de l'utilisateur

        //création du formulaire
        $form = $this->createForm(PaymentType::class, $payment);

        // remplissage du formulaire
        $form->handleRequest($request);

        //tester si la form est envoyé et est valid
        if ($form->isSubmitted() && $form->isValid())
        {
            //création de la commande
            $order = new Order();
            $order->setUser($user);
            $order->setAddress($payment->address);

            //transferer les articles du panier vers la commande
            foreach($user->getBasket()->getArticles() as $article){
                $order->addArticle($article);
            }

            //sauvegarde de la commande dans la bd
            $repository->add($order, true);

            //redirection vers la page de validation
            return $this->redirectToRoute('app_order_display');
        }




        return $this->render('order/display.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
