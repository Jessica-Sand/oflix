<?php

namespace App\Form\Type;

use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class CharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, [
                'label' => 'Prénom'
            ])
            ->add('lastname', null, [
                'label' => 'Nom'
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'homme' => 'Homme',
                    'femme' => 'Femme',
                ]
            ])
            ->add('bio')
            ->add('age')
            ->add('image', FileType::class, [
                'label' => 'Téléverser une nouvelle image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez ajouter une image valide',
                    ])
                ],
            ])
            ->add('Valider', SubmitType::class);
    }
}
