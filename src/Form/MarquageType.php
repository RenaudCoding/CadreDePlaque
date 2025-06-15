<?php

namespace App\Form;

use App\Entity\Typo;
use App\Entity\Marquage;
use App\Entity\Exemplaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class MarquageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('texteTypo', TextType::class)
            ->add('couleurTypo', TextType::class)
            ->add('tailleTypo', NumberType::class)
            ->add('typo', EntityType::class, [
                'class' => Typo::class,
                'choice_label' => 'nomTypo',
            ]);
            // ->add('exemplaire', EntityType::class, [
            //     'class' => Exemplaire::class,
            //     'choice_label' => 'id',
            // ])
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Marquage::class,
        ]);
    }
}
