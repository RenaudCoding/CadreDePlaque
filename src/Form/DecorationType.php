<?php

namespace App\Form;

use App\Entity\Decoration;
use App\Entity\Exemplaire;
use App\Entity\Logo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecorationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tailleLogo')
            ->add('logo', EntityType::class, [
                'class' => Logo::class,
                'choice_label' => 'nomLogo',
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
            'data_class' => Decoration::class,
        ]);
    }
}
