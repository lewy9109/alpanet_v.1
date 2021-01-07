<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$user = $options['user'];

        $builder
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, array(
                'type'=>PasswordType::class
            ))
           // ->add('company_name', TextType::class)
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('phone', TextType::class)
           // ->add('date_add')
           // ->add('img', FileType::class)
        ;

        // if ($user && in_array('ROLE_USER', $user->getRoles())) {
        //     $builder->add('company_name', TextType::class,[
        //         'empty_data'=>''
        //     ]);
        // }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            //'user'=>null
        ]);
    }
}
