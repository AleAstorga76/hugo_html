<?php
// src/Form/PriceEntryType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', IntegerType::class, [
                'label' => 'Cantidad',
                'attr' => [
                    'placeholder' => 'Ej: 4, 8, 16, 20...',
                    'min' => 1,
                ],
            ])
            ->add('price', NumberType::class, [
                'label' => 'Precio ($)',
                'scale' => 2,
                'attr' => [
                    'placeholder' => 'Ej: 18.50',
                    'step' => '0.01',
                    'min' => '0',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}