<?php
// src/Controller/Admin/ProductCrudController.php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Producto')
            ->setEntityLabelInPlural('Productos')
            ->setPageTitle('index', 'Gestión de Productos')
            ->setPageTitle('new', 'Crear Nuevo Producto')
            ->setPageTitle('edit', 'Editar Producto');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            
            TextField::new('name', 'Nombre')
                ->setHelp('Nombre del producto (ej: Hot Roll, Omakase)'),
                
            TextareaField::new('description', 'Descripción')
                ->setHelp('Describe los ingredientes y características del producto'),
                
            ChoiceField::new('category', 'Categoría')
                ->setChoices([
                    'Rolls' => 'rolls',
                    'Combos' => 'combos',
                    'Especialidades' => 'especialidades',
                ])
                ->setHelp('Selecciona la categoría del producto'),

            FormField::addPanel('Precios por Cantidad'),
            
            // TODOS LOS CAMPOS DE PRECIO COMO IntegerField
            IntegerField::new('price1', 'Precio x1 pieza')
                ->setFormTypeOption('attr', ['min' => 0])
                ->setRequired(false)
                ->hideOnIndex(),
                
            IntegerField::new('price4', 'Precio x4 piezas')
                ->setFormTypeOption('attr', ['min' => 0])
                ->setRequired(false)
                ->hideOnIndex(),
                
            IntegerField::new('price6', 'Precio x6 piezas')
                ->setFormTypeOption('attr', ['min' => 0])
                ->setRequired(false)
                ->hideOnIndex(),
                
            IntegerField::new('price8', 'Precio x8 piezas')
                ->setFormTypeOption('attr', ['min' => 0])
                ->setRequired(false)
                ->hideOnIndex(),
                
            IntegerField::new('price16', 'Precio x16 piezas')
                ->setFormTypeOption('attr', ['min' => 0])
                ->setRequired(false)
                ->hideOnIndex(),
                
            IntegerField::new('price20', 'Precio x20 piezas')
                ->setFormTypeOption('attr', ['min' => 0])
                ->setRequired(false)
                ->hideOnIndex(),
                
            IntegerField::new('price32', 'Precio x32 piezas')
                ->setFormTypeOption('attr', ['min' => 0])
                ->setRequired(false)
                ->hideOnIndex(),
                
            IntegerField::new('price36', 'Precio x36 piezas')
                ->setFormTypeOption('attr', ['min' => 0])
                ->setRequired(false)
                ->hideOnIndex(),
                
            IntegerField::new('price40', 'Precio x40 piezas')
                ->setFormTypeOption('attr', ['min' => 0])
                ->setRequired(false)
                ->hideOnIndex(),
                
            IntegerField::new('price48', 'Precio x48 piezas')
                ->setFormTypeOption('attr', ['min' => 0])
                ->setRequired(false)
                ->hideOnIndex(),
                
            IntegerField::new('price50', 'Precio x50 piezas')
                ->setFormTypeOption('attr', ['min' => 0])
                ->setRequired(false)
                ->hideOnIndex(),

            BooleanField::new('stock', 'En stock')
                ->setHelp('¿El producto está disponible?'),
                
            ImageField::new('image', 'Imagen')
                ->setBasePath('images/products/')
                ->setUploadDir('public_html/images/products/')
                ->setRequired(false)
                ->setHelp('Sube una imagen del producto'),
        ];
    }
}