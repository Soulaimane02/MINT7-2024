<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Address;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' =>[
                    'placeholder' => 'Jhon'
                ]

            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' =>[
                    'placeholder' => 'Doe'
                ]

            ])
            ->add('address' ,TextType::class, [
                'label' => 'Adresse',
                'attr' =>[
                    'placeholder' => '25 rue Dhoe, Paris 16'
                ]

            ])
            ->add('postal', TextType::class, [
                'label' => 'Code Postal',
                'attr' =>[
                    'placeholder' => '75016'
                ]

            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' =>[
                    'placeholder' => 'Paris'
                ]

            ])
            ->add('contry', CountryType::class, [
                'label' => 'Pays',
                'attr' =>[
                    'placeholder' => 'France'
                ]

            ])
            ->add('phone', TextType::class, [
                'label' => 'Numéro de téléphone',
                'attr' =>[
                    'placeholder' => '06-77-07-92-12'
                ]

            ])
            ->add('Valider',SubmitType::class)
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
