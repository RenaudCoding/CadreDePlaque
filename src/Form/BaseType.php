<?php

namespace App\Form;

use App\Entity\Base;
use App\Entity\Exemplaire;
use App\Entity\Fond;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fond', EntityType::class, [
                'class' => Fond::class,
                'choice_label' => 'couleurFond',
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
            'data_class' => Base::class,
        ]);
    }
}
