<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Produit;
use App\Entity\Exemplaire;
use App\Form\MarquageType;
use App\Form\DecorationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ExemplaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomExemplaire', TextType::class, [
                'required' => false
            ])
            // ->add('dateCreation', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
            // ->add('produit', EntityType::class, [
            //     'class' => Produit::class,
            //     'choice_label' => 'id',
            // ])

            ->add('bases', CollectionType::class, [
                'entry_type' => BaseType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
                'label' => false,
            ])

            ->add('decorations', CollectionType::class, [
                'entry_type' => DecorationType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required'   => false,
                'label' => false,
            ])
            
            ->add('marquages', CollectionType::class, [
                'entry_type' => MarquageType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required'   => false,
                'label' => false
            ])
            ->add('Valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exemplaire::class,
        ]);
    }
}
