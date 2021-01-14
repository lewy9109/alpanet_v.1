<?php

namespace App\Form;

use App\Entity\CustomerDomain;
use App\Entity\Pakiet;
use App\Entity\User;
use App\Repository\CustomerDomainRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPakietType extends AbstractType
{

//    private $customerDomainRepository;
//
//    public function __construct(CustomerDomainRepository $customerDomainRepository) {
//
//        $this->$customerDomainRepository = $customerDomainRepository;
//    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder

            ->add('user', EntityType::class, array(
                    'class'=>User::class,
                    'choice_label'=>'email',
                    'placeholder'=>'Wybierz klienta',
                    'label'=>'Klient'
                )
            )
            ->add('pakiet_time', ChoiceType::class, array(
                'choices'=>array(
                    1=>"60",
                    2=>"120",
                ),
                'placeholder'=>'Wybierz',
                'label'=> "PakietManage w godzinach"
            ))
            ->add('settlement', ChoiceType::class, array(
                'choices'=>array(
                    "Miesięczny"=>1,
                    "Kwartalny"=>2,
                    "Półroczny"=>3,
                    "Roczny"=>4,
                ),
                'label'=> "Okres rozliczenia",
                'placeholder'=>'Wybierz',
            ))
            ->add('pakiet_start', DateType::class, [
                'widget' => 'choice',
                'input'  => 'datetime',
               // 'format' => 'MM-dd-yyyy',

            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class'=>Pakiet::class
        ]);
    }
}
