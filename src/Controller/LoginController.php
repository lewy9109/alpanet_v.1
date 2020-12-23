<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/", name="login_front")
     */
    public function login(): Response
    {
        return $this->render('login/login_front.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    /**
     * @Route("/forget-password", name="forget_password")
     */
    public function forgetPassword(): Response
    {
       
        return $this->render('login/forget_password.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
}
