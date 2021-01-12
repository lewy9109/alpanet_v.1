<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/front")
 */
class FrontController extends AbstractController
{
    /**
     * @Route("/", name="front_main_page")
     */
    public function index(): Response
    {
       
        return $this->render('front/main_panel.html.twig');
    }

    /**
     * @Route("/employee-list", name="front_employee_list")
     */
    public function employeList(): Response
    {
        return $this->render('front/employee_list.html.twig');
    }

    
}
