<?php
// src/Field/PricesField.php

namespace App\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

final class PricesField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('admin/field/prices.html.twig')
            ->setFormType(TextareaType::class)
            ->setFormTypeOptions([
                'attr' => [
                    'rows' => 5,
                    'placeholder' => '{"4": 18.50, "8": 32.00}',
                    'class' => 'font-monospace'
                ],
                'help' => 'Formato JSON: {"cantidad": precio, "cantidad": precio}'
            ]);
    }
}