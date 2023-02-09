<?php

namespace App\Controller;

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
}
