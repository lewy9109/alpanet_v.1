<?php

namespace App\Form;

use App\Entity\CustomerDomain;
use App\Entity\User;
use App\Form\CustomerDomainType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = $options['data'];
      //  dd($roles);
        // By default, password is required (create user case)
        $passwordOptions = array(
            'type'           => PasswordType::class,
            'first_options'  => ['label' => 'Hasło'],
            'second_options' => ['label' => 'Powtórz hasło'],
            'required'       => true
        );

        // If edit user : password is optional
        // User object is stored in $options['data']
        $recordId = $options['data']->getId();
        if (!empty($recordId)) {
            $passwordOptions['required'] = false;
        }

        if(in_array('ROLE_USER', $roles->getRoles()))
        {

            $builder
                ->add('company_name', TextType::class,[
                'label'=>"Nazwa firmy"
                ])

                ;
        }

        $builder
            ->add('email', EmailType::class,[
                'label'=>"Email"
            ])
            ->add('name', TextType::class, [
                'label'=>'Imię'
            ])
            ->add('surname', TextType::class,[
                'label'=>'Nazwisko'
            ])
            ->add('phone', TextType::class,[
                'label'=>'Telefon'
            ])
            ->add('password', RepeatedType::class, $passwordOptions)
        ;



    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
