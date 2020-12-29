<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FrontController extends AbstractController
{
    /**
     * @Route("/front", name="front_main_page")
     */
    public function index(): Response
    {
        return $this->render('front/index2.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    
}
