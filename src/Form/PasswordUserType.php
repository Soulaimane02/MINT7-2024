<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPwd',PasswordType::class,
                   ['label' => 'Votre mot de passe actuel',
                   'attr' => ['placeholder' => 'Entrez votre mot de passe actuel'],
                   'mapped' => false

                ],

                  
            )
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Choisissez votre nouveau mot de passe',
                'attr' => 
                [
                    'placeholder' => 'Choisissez votre nouveau mot de passe'

                ],
                'hash_property_path' => 'password'
            ],

                'second_options' => 
                [   
                    'label' => 'Confirmer votre nouveau mot de passe',
                    'attr' => 
                    [
                        'placeholder' => 'Confirmer votre nouveau mot de passe'

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
            ->add('submit', SubmitType::class,[
                'label' => 'Mettre à jour le mot de passe '
            ])
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event){
                $form = $event->getForm();
                $user= $form->getConfig()->getOptions()['data'];
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];
                $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $form->get('actualPwd')->getData()
                );
                if(!$isValid)
                {
                    $form->get('actualPwd')->addError(new FormError("Votre mot de passe actuel n'est pas conforme !"));

                }
            })

            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null
        ]);
    }
}
