<?php
// src/Controller/Admin/SaleCrudController.php

namespace App\Controller\Admin;

use App\Entity\Sale;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class SaleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sale::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Venta')
            ->setEntityLabelInPlural('Ventas')
            ->setPageTitle('index', 'Gestión de Ventas')
            ->setPageTitle('new', 'Nueva Venta')
            ->setPageTitle('edit', 'Editar Venta');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('product', 'Producto')
                ->setRequired(true),
            
            ChoiceField::new('quantity', 'Cantidad de Piezas')
                ->setChoices([
                    '1 pieza' => 1,
                    '4 piezas' => 4,
                    '6 piezas' => 6,
                    '8 piezas' => 8,
                    '16 piezas' => 16,
                    '20 piezas' => 20,
                    '32 piezas' => 32,
                    '36 piezas' => 36,
                    '40 piezas' => 40,
                    '48 piezas' => 48,
                    '50 piezas' => 50,
                ])
                ->setRequired(true),
            
            IntegerField::new('unitPrice', 'Precio Unitario ($)')
                ->setRequired(true)
                ->setFormTypeOption('attr', ['min' => 0])
                ->hideOnIndex(),

            IntegerField::new('totalAmount', 'Total ($)')
                ->setRequired(true)
                ->setFormTypeOption('attr', ['min' => 0])
                ->onlyOnIndex(),
            
            DateTimeField::new('saleDate', 'Fecha de Venta')
                ->setFormat('dd/MM/yyyy HH:mm')
                ->setRequired(true),
            
            ChoiceField::new('status', 'Estado')
                ->setChoices([
                    '⏳ Pendiente' => Sale::STATUS_PENDING,
                    '✅ Confirmado' => Sale::STATUS_CONFIRMED,
                    '❌ Cancelado' => Sale::STATUS_CANCELLED,
                ])
                ->renderAsBadges(),
            
            TextField::new('customerName', 'Cliente')
                ->hideOnIndex(),
            
            TextField::new('customerPhone', 'Teléfono')
                ->hideOnIndex(),
            
            TextareaField::new('customerAddress', 'Dirección')
                ->hideOnIndex(),
            
            TextareaField::new('observations', 'Observaciones')
                ->hideOnIndex(),
            
            TextareaField::new('notes', 'Notas Internas')
                ->hideOnIndex(),
        ];
    }
}