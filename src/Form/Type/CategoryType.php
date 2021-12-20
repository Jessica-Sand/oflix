<?php

namespace App\Form\Type;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // input text car la propriété name de l'entité est de type string
        // Paramètres de la méthode add : 
        // - $child ('name') ==> Le nom de la propriété de l'entité ...mais pas que
        // - $type ==> le type du champs html à générer. 
        //              Si on ne précise pas la valeur de ce paramètre, Symfony
        //              se servira du type de la propriété (string, integer, text, ..)
        //              pour décider de la balise HTML à générer
        // - $options ==> Option de l'élément HTML, pour customisation du formulaire
        //            ==> label, attr, required, ...
        $builder->add('name', null, [
            'label' => 'Nom de la catégorie'
        ]);

        // Bouton de validation
        $builder->add('save', SubmitType::class, [
            'label' => 'Valider',
            'attr' => [
                'class' => 'btn-danger viviane_css'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'hello' => null
        ]);
    }
}
