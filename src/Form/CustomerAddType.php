<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('company_name', EmailType::class,[
                'label'=>"Nazwa firmy"
            ])
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
            ->add('password', RepeatedType::class, array(
                'type'=>PasswordType::class,
                'first_options'  => ['label' => 'Hasło'],
                'second_options' => ['label' => 'Powtórz hasło'],
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
