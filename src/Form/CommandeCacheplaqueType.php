<?php

namespace App\Form;

use App\Entity\Panier;
use App\Entity\Commande;
use App\Entity\Exemplaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class CommandeCacheplaqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('exemplaireAvant', HiddenType::class, [
                'mapped' => false // car en JS on récupère une id et non une entité
                ])
            ->add('quantiteAvant', IntegerType::class, [
                'mapped' => false, //car le champ n'existe pas dans l'entité
                'data' => '0',
                // 'constraints' => [new GreaterThanOrEqual( 0 , null, "Il faut un nombre positif ou nul")],
                'attr' => ['min' => 0,
                            'disabled' => true] // on rendra le champ accessible en JS lorsqu'une quantité > 0 est saisie]
                ])
            ->add('exemplaireArriere',HiddenType::class, [
                'mapped' => false // car en JS on récupère une id et non une entité
                ])    
            ->add('quantiteArriere', IntegerType::class, [
                'mapped' => false, //car le champ n'existe pas dans l'entité
                'data' => '0',
                // 'constraints' => [new GreaterThanOrEqual( 0 , null, "Il faut un nombre positif ou nul")],
                'attr' => ['min' => 0,
                            'disabled' => true] // on rendra le champ accessible en JS lorsqu'une quantité > 0 est saisie]]
                ])
            ->add('validation', CheckboxType::class, [
                'mapped' => false,
                'label'    => 'Après vérification, je valide la décoration qui doit être imprimée sur les caches plaque',
                'required' => true, // la case doit être cochée
                'attr' => ['disabled' => true]
                ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter au panier',
                'attr' => ['disabled' => true,
                            'class' => 'btn-vert']
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
        ]);
    }
}
