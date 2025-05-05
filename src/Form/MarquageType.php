<?php

namespace App\Form;

use App\Entity\Exemplaire;
use App\Entity\Marquage;
use App\Entity\Typo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MarquageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('texteTypo')
            ->add('couleurTypo')
            ->add('tailleTypo')
            ->add('typo', EntityType::class, [
                'class' => Typo::class,
                'choice_label' => 'nomTypo',
            ])
            // ->add('exemplaire', EntityType::class, [
            //     'class' => Exemplaire::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Marquage::class,
        ]);
    }
}
