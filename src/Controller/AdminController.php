<?php

namespace App\Controller;

use App\Entity\CustomerDomain;
use App\Entity\User;
use App\Form\CustomerAddType;
use App\Form\UserCustomerType;
use App\Form\UserPakietType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
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
        $user->setRoles(['ROLE_MODERATOR']);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $user->setName($request->request->get('user')['name']);
            $user->setSurname($request->request->get('user')['surname']);
            $user->setEmail($request->request->get('user')['email']);
            $user->setPhone($request->request->get('user')['phone']);
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
     * @Route ("/add-customer", name="admin_add_customer")
     */
    public function addCustomer(Request $request, UserPasswordEncoderInterface $encode_password):Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);

        $customerDomain = new CustomerDomain();

        $form = $this->createForm(UserCustomerType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $user->setName($request->request->get('user_customer')['name']);
            $user->setSurname($request->request->get('user_customer')['surname']);
            $user->setEmail($request->request->get('user_customer')['email']);
            $user->setPhone($request->request->get('user_customer')['phone']);
            $user->setCompanyName($request->request->get('user_customer')['company_name']);

            $date = new \DateTime();
            $user->setDateAdd($date);
            $password = $encode_password->encodePassword($user, $request->request->get('user_customer')['password']['first']);
            $user->setPassword($password);

            $customerDomain->setNameDomain($request->request->get('user_customer')['domain']);
            $user->addCustomerDomain($customerDomain);

            $entityManager->persist($user);
            $entityManager->persist($customerDomain);

           // dd($customerDomain, $user);
            $entityManager->flush();


            $this->addFlash(
                'Sukces',
                'Dodano Klienta'
            );

            return $this->redirectToRoute('front_employee_list');

        }

        return $this->render('admin/admin_add.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route ("/add-pakiet", name="admin_add_pakiet")
     */
    public function addPakiet(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(UserPakietType::class);
        $form->handleRequest($request);
        return $this->render('admin/admin_add.html.twig', [
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

        $originalPassword = $user->getPassword();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $user->setName($request->request->get('user')['name']);
            $user->setSurname($request->request->get('user')['surname']);
            $user->setEmail($request->request->get('user')['email']);
            $user->setPhone($request->request->get('user')['phone']);
            //$user->setRoles(['ROLE_MODERATOR']);

            // Encode the password if needed
            # password has changed
            if (!empty($request->request->get('user')['password']['first'])) {
                $password = $encode_password->encodePassword($user, $request->request->get('user')['password']['first']);
                $user->setPassword($password);
                # password not changed
            } else {
                $user->setPassword($originalPassword);
            }

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
