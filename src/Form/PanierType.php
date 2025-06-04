<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PanierType extends AbstractType
{   
    // formulaire de validation du panier (modification des quantités commandées)
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // on ajoute un champ "articles" qui est une collection de formulaire 
        $builder->add('articles', CollectionType::class, [
            'entry_type' => QuantiteType::class, // QuantiteType est le formulaire qui gère la quantité dans le panier
            'allow_add' => false,
            'allow_delete' => false,
            'by_reference' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // vide car le formulaire n'est lié à aucune classe
        ]);
    }
}
