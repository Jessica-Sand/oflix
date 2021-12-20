<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, array(
                'choices' => [
                    'Super Administrateur' => 'ROLE_SUPER_ADMIN',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Editeur' => 'ROLE_EDITOR',
                ],
                'multiple' => true,
                'expanded' => true,
                )
            )
            // ->add('plainPassword', PasswordType::class, [
            //     // instead of being set onto the object directly,
            //     // this is read and encoded in the controller
            //     'label' => 'Mot de passe',
            //     'mapped' => false,
            //     'attr' => ['autocomplete' => 'new-password'],
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Veuillez saisir un mot de passe',
            //         ]),
            //         new Length([
            //             'min' => 6,
            //             'minMessage' => 'Votre mot de passe doit être au minimum de {{ limit }} caractères',
            //             // max length allowed by Symfony for security reasons
            //             'max' => 4096,
            //         ]),
            //     ],
            // ])
            ->add('firstname', null, [
                'label' => 'Prénom',
            ])
            ->add('lastname', null, [
                'label' => 'Nom',
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                // Avant construction du formulaire, on va d'abord vérifier dans
                // quel contexte on se trouve :
                // - Création : on rendra obligatoire la saisie d'un mot de passe
                // - Edition : la saisie du mot de passe sera facultative
                $form = $event->getForm();
                $userData = $event->getData();

                if ($userData->getId() === null) {
                    // Mode création
                    // Le mot de passe sera obligatoire
                    $required = true;
                    // $form->add('password', PasswordType::class, [
                    //     'mapped' => false,
                    //     'required' => $required
                    // ]);
                } else {
                    // Mode édition
                    // Le mot de passe ne pas sera obligatoire
                    $required = false;
                }

                // On ajoute dynamiquement le champ Password
                // Qui est obligatoire en création
                // et optionnel en édition
                $form->add('password', PasswordType::class, [
                    'mapped' => false,
                    'required' => $required
                ]);
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
