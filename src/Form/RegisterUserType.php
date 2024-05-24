<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;






class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, [
                'attr' => [
                    'placeholder' => "Doe"
                ]
            ])
            ->add('prenom',TextType::class, [
                'attr' => [
                    'placeholder' => "John"
                ]
            ])
            ->add('email', EmailType::class, 
            [
                'label' => "Email",
                'attr' =>[
                    'placeholder' => "admin@mint7.com"
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Mot de passe',
                'attr' => 
                [
                    'placeholder' => 'azerty'

                ],
                'hash_property_path' => 'password'
            ],

                'second_options' => 
                [   
                    'label' => 'Confirmer le mot de passe',
                    'attr' => 
                    [
                        'placeholder' => 'azerty'

                    ]
            ],
            'mapped' => false,
            'invalid_message' => 'Les mots de passe ne correspondent pas.',
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer un mot de passe.',
                ]),
                
                new Length([
                    'min' => 6,
                    'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                    'max' => 4096,
                ]),
                new Regex([
                    'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/',
                    'message' => 'Votre mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial.',
                ]),
            ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => '<a href="conditions/utilisations">Accepter nos conditions d\'utilisation</a>',
                'label_html' => true, // Permet d'interpréter le label comme du HTML
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions d\'utilisation !.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class,
            [
                'label' => "Valider",
                'attr' => [
                    'class' => 'btn btn-success'

                ]
            ])
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' =>
            [
                new UniqueEntity
                (
                    [
                        'entityClass' => User::class,
                        'fields' => 'email'
                    ]
                )
            ],
            'data_class' => User::class,
        ]);
    }
}
