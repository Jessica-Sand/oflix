<?php

namespace App\Form;

use App\Entity\TvShow;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TvShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre de la série',
                'required' => true,

            ])
            ->add('synopsis', null, [
                'label' => 'Synopsis'
            ])
            ->add('image', FileType::class, [
                'label' => 'Image pour illustrer',
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
            ->add('nbLikes', null, [
                'label' => 'Nombre de likes'
            ])
            ->add('publishedAt', null, [
                'label' => 'Date de publication'
            ])
            ->add('createdAt', null, [
                'label' => 'Date de création'
            ])
            ->add('updatedAt', null, [
                'label' => 'Date de mise à jour'
            ])
            ->add('characters', null, [
                'label' => 'Les personnages',
                'required' => true,
            ])
            ->add('categories', null, [
                'label' => 'Les catégories',
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Valider',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TvShow::class,
        ]);
    }
}
