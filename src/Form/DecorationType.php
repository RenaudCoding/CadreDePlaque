<?php

namespace App\Form;

use App\Entity\Logo;
use App\Entity\Decoration;
use App\Entity\Exemplaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DecorationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('logo', EntityType::class, [
                'class' => Logo::class,
                'choice_label' => 'nomLogo',
                'required'   => false
            ])
            ->add('tailleLogo', NumberType::class, [
                'required'   => false
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
