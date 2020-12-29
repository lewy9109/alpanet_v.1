<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\User;

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

     /**
     * @Route("/sing-In", name="sing_in")
     */
    public function SingIn(): Response
    {
       echo "siema";
       dump($_POST);
       $user = User::authenticate($_POST['email'], $_POST['password']);
       dump($user);
   
        return $this->redirectToRoute('front_main_page');
    }
}
