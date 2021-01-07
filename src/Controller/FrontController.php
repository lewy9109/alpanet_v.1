<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/front")
 */
class FrontController extends AbstractController
{
    /**
     * @Route("/", name="front_main_page")
     */
    public function front(): Response
    {
        return $this->render('front/main_panel.html.twig');
    }

    /**
     * @Route("/employee-list", name="front_employee_list")
     */
    public function employeList(): Response
    {
      
        $employee = $this->getDoctrine()->getRepository(User::class)
        ->findByRole('ROLE_MODERATOR');
       
    
        return $this->render('front/employee_list.html.twig', [
            'users'=>$employee,
        ]);
    }

    /**
     * @Route("/add-employee", name="admin_add_employee")
     */
    public function addEmployee(Request $request, UserPasswordEncoderInterface $encode_password): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $user->setName($request->request->get('user')['name']);
            $user->setSurname($request->request->get('user')['surname']);
            $user->setEmail($request->request->get('user')['email']);
            $user->setPhone($request->request->get('user')['phone']);
            $user->setRoles(['ROLE_MODERATOR']);
            $password = $encode_password->encodePassword($user, $request->request->get('user')['password']['first']);
            $user->setPassword($password);
            $date = new \DateTime();
            $user->setDateAdd($date);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('front_employee_list');
        }
        return $this->render('front/admin_add_employee.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_delete")
     */
    public function delete(int $id): Response
    {
       
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->render('front/main_panel.html.twig');
    }

    /**
     * @Route("/edit/{id}", name="admin_edit")
     */
    public function edit(int $id, Request $request): Response
    {
        
        $user = $this->getDoctrine()->getRepository(User::class)
        ->find($id);
        
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        $is_invalid = false;
        // $this->addFlash(
        //     'succes',
        //     'Twoje zmiany zostały zapisane'
        // );
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash(
                'succes',
                'Twoje zmiany zostały zapisane'
            );
            $this->redirectToRoute('front_employee_list');
        }   
        
        return $this->render('front/admin_edit.html.twig',[
            'form'=>$form->createView(),
            'user'=>$user,
            'is_invalid'=>$is_invalid
        ]);
    }

     /**
     * @Route("/customer-list", name="front_customer_list")
     */
    public function customerList(): Response
    {
      
        $customers = $this->getDoctrine()->getRepository(User::class)
        ->findByRole('ROLE_USER');
       
    
        return $this->render('front/customer_list.html.twig', [
            'users'=>$customers,
        ]);
    }

    /**
     * @Route("/add-employee", name="admin_add_employee")
     */
    public function addCustomer(Request $request, UserPasswordEncoderInterface $encode_password): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $user->setName($request->request->get('user')['name']);
            $user->setSurname($request->request->get('user')['surname']);
            $user->setEmail($request->request->get('user')['email']);
            $user->setPhone($request->request->get('user')['phone']);
            $user->setRoles(['ROLE_MODERATOR']);
            $password = $encode_password->encodePassword($user, $request->request->get('user')['password']['first']);
            $user->setPassword($password);
            $date = new \DateTime();
            $user->setDateAdd($date);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('front_employee_list');
        }
        return $this->render('front/admin_add_employee.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

}
