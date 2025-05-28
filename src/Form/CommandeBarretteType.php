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
                'data' => '1',
                'constraints' => [new GreaterThanOrEqual( 1 , null, "Il faut un nombre positif ou nul")],
                'attr' => ['min' => 1]
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
