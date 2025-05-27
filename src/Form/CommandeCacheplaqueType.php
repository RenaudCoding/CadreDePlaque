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
                ])
            ->add('exemplaireArriere',HiddenType::class, [
                'mapped' => false // car en JS on récupère une id et non une entité
                ])    
            ->add('quantiteArriere', IntegerType::class, [
                'mapped' => false, //car le champ n'existe pas dans l'entité
                ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
        ]);
    }
}
