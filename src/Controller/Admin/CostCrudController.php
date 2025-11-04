<?php
// src/Controller/Admin/CostCrudController.php

namespace App\Controller\Admin;

use App\Entity\Cost;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cost::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('description', 'Descripción')
                ->setRequired(true),
            
            IntegerField::new('amount', 'Monto ($)')
                ->setRequired(true)
                ->setFormTypeOption('attr', ['min' => 0]),
            
            DateTimeField::new('costDate', 'Fecha del Costo')
                ->setFormat('dd/MM/yyyy HH:mm')
                ->setRequired(true),
            
            ChoiceField::new('category', 'Categoría')
                ->setChoices([
                    'Ingredientes' => 'ingredientes',
                    'Personal' => 'personal',
                    'Alquiler' => 'alquiler',
                    'Servicios' => 'servicios',
                    'Marketing' => 'marketing',
                    'Otros' => 'otros',
                ])
                ->setRequired(true),
            
            TextareaField::new('notes', 'Notas')
                ->hideOnIndex(),
        ];
    }
}