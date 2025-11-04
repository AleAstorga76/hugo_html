<?php
// src/Form/PricesCollectionType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PricesCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            // Convierte el array asociativo al formato del CollectionField
            $collectionData = [];
            if (is_array($data)) {
                foreach ($data as $quantity => $price) {
                    $collectionData[] = [
                        'quantity' => $quantity,
                        'price' => $price
                    ];
                }
            }

            $event->setData($collectionData);
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $data = $event->getData();

            // Convierte el CollectionField de vuelta al array asociativo
            $pricesArray = [];
            if (is_array($data)) {
                foreach ($data as $item) {
                    if (isset($item['quantity']) && isset($item['price']) && $item['quantity'] && $item['price']) {
                        $quantity = (int)$item['quantity'];
                        $price = (float)$item['price'];
                        $pricesArray[$quantity] = $price;
                    }
                }
            }

            $event->setData($pricesArray);
        });
    }

    public function getParent(): string
    {
        return \EasyCorp\Bundle\EasyAdminBundle\Form\Type\CrudFormType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'compound' => true,
        ]);
    }
}