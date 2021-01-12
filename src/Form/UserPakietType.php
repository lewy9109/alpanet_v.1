<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPakietType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pakiet_on_hours', ChoiceType::class, array(
                'choices'=>array(
                    1=>"60",
                    2=>"120",
                ),
                'label'=> "Pakiet w godzinach"
            ))
            ->add('settlement', ChoiceType::class, array(
                'choices'=>array(
                    "Miesięczny"=>1,
                    "Kwartalny"=>2,
                    "Półroczny"=>3,
                    "Roczny"=>4,
                ),
                'label'=> "Okres rozliczenia"
            ))
            ->add('user', EntityType::class, array(
                'class'=>User::class,

            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
