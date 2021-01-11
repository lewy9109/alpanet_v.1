<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CustomerAddType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin", )
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/employee-list", name="front_employee_list")
     */
    public function employeList(): Response
    {

        $employee = $this->getDoctrine()->getRepository(User::class)
            ->findByRole('ROLE_MODERATOR');


        return $this->render('admin/employee_list.html.twig', [
            'users'=>$employee,
        ]);
    }



    /**
     * @Route("/customer-list", name="front_customer_list")
     */
    public function customerList(): Response
    {

        $customers = $this->getDoctrine()->getRepository(User::class)
            ->findByRole('ROLE_USER');


        return $this->render('admin/customer_list.html.twig', [
            'users'=>$customers,
        ]);
    }


    /**
     * @Route("/add-employee", name="admin_add_employee")
     *
     */
    public function addEmployee(Request $request, UserPasswordEncoderInterface $encode_password): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class);
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

            $this->addFlash(
                'Sukces',
                'Dodano pracownika'
            );

            return $this->redirectToRoute('front_employee_list');
        }
        return $this->render('admin/admin_add_employee.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route ("/add-custome", name="admin_add_customer")
     */
    public function addCustomer(Request $request, UserPasswordEncoderInterface $encode_password):Response
    {
        $user = new User();
        $form = $this->createForm(CustomerAddType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {

            $entityManager = $this->getDoctrine()->getManager();

            $user->setName($request->request->get('user')['name']);
            $user->setSurname($request->request->get('user')['surname']);
            $user->setEmail($request->request->get('user')['email']);
            $user->setPhone($request->request->get('user')['phone']);
            $user->setCompanyName($request->request->get('user')['company_name']);
            $user->setRoles(['ROLE_USER']);
            $password = $encode_password->encodePassword($user, $request->request->get('user')['password']['first']);
            $user->setPassword($password);
            $date = new \DateTime();
            $user->setDateAdd($date);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'Sukces',
                'Dodano pracownika'
            );

            return $this->redirectToRoute('front_employee_list');
        }
        return $this->render('admin/admin_add_customer.html.twig', [
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
     *
     */
    public function edit(User $user, Request $request, UserPasswordEncoderInterface $encode_password): Response
    {

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
            if(isset($request->request->get('user')['password']['first']))
            {
                $password = $encode_password->encodePassword($user, $request->request->get('user')['password']['first']);
                $user->setPassword($password);
            }

            $date = new \DateTime();
            $user->setDateAdd($date);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'Sukces',
                'Dodano pracownika'
            );

            return $this->redirectToRoute('front_employee_list');
        }
        return $this->render('admin/admin_edit_user.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

}
