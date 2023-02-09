<?php

namespace App\Controller;

use App\Entity\Pizza;
use App\Entity\Article;
use App\Repository\BasketRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
 * @IsGranted("ROLE_USER")
 */
class BasketController extends AbstractController
{
    /**
     * @Route("/mon-panier", name="app_basket_display")
     */
    public function display(): Response
    {
        return $this->render('basket/display.html.twig');
    }


    /**
     * @Route("/mon-panier/{id}/ajouter", name="app_basket_addArticle")
     */
    public function addArticle(Pizza $pizza, BasketRepository $repository): Response{

        //récuperer l'utilisateur et son panier
        $user= $this->getUser();
        $basket= $user->getBasket();

        //créer un nouvel article à mettre dans le panier
        $article= new Article();
        $article->setQuantity(1);
        $article->setBasket($basket); // relier l'article au panier
        $article->setPizza($pizza); // relier l'article à la pizza choisie


        //ajouter l'article au panier
        $basket->addArticle($article);

        //sauvegarde du panier dans la bd:
        $repository->add($basket, true);

        //redirection vers le panier
        return $this->redirectToRoute("app_basket_display");
    }



    /**
     * @Route("/mon-panier/{id}/plus", name="app_basket_plus")
     */
    public function plus(Article $article, ArticleRepository $repository): Response
    {
        //mettre la quantité à +1
        $qt= $article->getQuantity();
        $article->setQuantity($qt+1);


        //sauvegarde de la nouvelle quantité
        $repository->add($article, true);

        //redirection vers le panier
        return $this->redirectToRoute("app_basket_display");
    }


    /**
     * @Route("/mon-panier/{id}/diminuer", name="app_basket_minus")
     */
    public function minus(Article $article, ArticleRepository $repository, BasketRepository $basketRepo): Response
    {
         //mettre la quantité à -1
         $qt= $article->getQuantity();
         $article->setQuantity($qt-1);

         //test si la quatité est à 0
         if ($article->getQuantity() <= 0){
            //supprimmer l'article du panier

            //1. recuperer l'utilisateur puis son panier
            $user= $this->getUser();
            $basket= $user->getBasket();

            //2. supprimer de l'entité basket l'article
            $basket->removeArticle($article);

            //mettre à jour la bd via le besketRepo
            $basketRepo->add($basket, true);

            //redirection vers le panier
            return $this->redirectToRoute("app_basket_display");

         }


         //sauvegarde de la nouvelle quantité
         $repository->add($article, true);

         //redirection vers le panier
        return $this->redirectToRoute("app_basket_display");

    }

}
