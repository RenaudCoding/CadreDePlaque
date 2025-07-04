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

class CommandeBarretteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('exemplaire',HiddenType::class, [
                'mapped' => false // car en JS on récupère une id et non une entité
                ])
            ->add('quantite', IntegerType::class, [
                'label' => false,
                'data' => '0',
                // 'constraints' => [new GreaterThanOrEqual( 0 , null, "Problème de quantité")],
                'attr' => ['min' => 0,
                            'disabled' => true] // on rendra le champ accessible en JS une fois qu'un exemplaire est sélectionné               
            ])
            ->add('validation', CheckboxType::class, [
                'mapped' => false,
                'label'  => 'Après vérification, je valide la décoration qui doit être imprimée sur les barrettes',
                'required' => true, // la case doit être cochée
                'attr' => ['disabled' => true] // on rendra le champ accessible en JS lorsqu'une quantité > 0 est saisie
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter au panier',
                'attr' => ['disabled' => true, // on rendra le champ accessible en JS une fois que la checkbox est cochée
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
