<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\User;

use Exception;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/login-front", name="login_front")
     */
    public function login(AuthenticationUtils $helper): Response
    {
        return $this->render('login/login_front.html.twig', [
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }
  
    /**
     * @Route("/forget-password-front", name="forget_password_front")
     */
    public function forgetPassword(): Response
    {
       
        return $this->render('login/forget_password.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

     

}
