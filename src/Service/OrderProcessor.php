<?php
// src/Service/OrderProcessor.php

namespace App\Service;

use App\Entity\Sale;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class OrderProcessor
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function processWhatsAppOrder(array $cart, array $customerInfo = []): array
    {
        $createdSales = [];

        foreach ($cart as $item) {
            // Buscar el producto por nombre
            $product = $this->entityManager->getRepository(Product::class)
                ->findOneBy(['name' => $item['name']]);

            if (!$product) {
                // Si no encuentra el producto, crear uno genérico
                $sale = $this->createGenericSale($item, $customerInfo);
            } else {
                // Crear venta con el producto encontrado
                $sale = $this->createSaleFromProduct($product, $item, $customerInfo);
            }

            $this->entityManager->persist($sale);
            $createdSales[] = $sale;
        }

        $this->entityManager->flush();

        return $createdSales;
    }

    private function createSaleFromProduct(Product $product, array $item, array $customerInfo): Sale
    {
        $sale = new Sale();
        $sale->setProduct($product);
        
       // CORRECCIÓN: Usar pieceCount para la cantidad de piezas
        $sale->setQuantity($item['pieceCount'] ?? $item['quantity']);
        
        // Calcular precio unitario correctamente usando el formateador
        $totalPrice = floatval($item['price']);
        $quantity = $item['pieceCount'] ?? $item['quantity'];
        $unitPrice = $totalPrice / $quantity;
        
        $sale->setUnitPrice((string) $unitPrice);
        $sale->setTotalAmount((string) $totalPrice);
        $sale->setStatus(Sale::STATUS_PENDING);
        
        
        // Información del cliente
        $sale->setCustomerName($customerInfo['name'] ?? 'Cliente WhatsApp');
        $sale->setCustomerPhone($customerInfo['phone'] ?? '');
        $sale->setCustomerAddress($customerInfo['address'] ?? '');
        $sale->setObservations($customerInfo['observations'] ?? 'Pedido desde WhatsApp');
        
        $sale->setNotes(sprintf(
            "Pedido automático desde WhatsApp - %s - %d pedido(s) de %d piezas",
            date('d/m/Y H:i'),
            $item['quantity'],
            $item['pieceCount'] ?? $item['quantity']
        ));

        return $sale;
    }

    private function createGenericSale(array $item, array $customerInfo): Sale
    {
        $sale = new Sale();
        
         // CORRECCIÓN: Usar pieceCount para la cantidad de piezas
        $sale->setQuantity($item['pieceCount'] ?? $item['quantity']);
        
        // Calcular precio unitario correctamente usando el formateador
        $totalPrice = floatval($item['price']);
        $quantity = $item['pieceCount'] ?? $item['quantity'];
        $unitPrice = $totalPrice / $quantity;
        
        $sale->setUnitPrice((string) $unitPrice);
        $sale->setTotalAmount((string) $totalPrice);
        $sale->setStatus(Sale::STATUS_PENDING);
        
        // Información del cliente
        $sale->setCustomerName($customerInfo['name'] ?? 'Cliente WhatsApp');
        $sale->setCustomerPhone($customerInfo['phone'] ?? '');
        $sale->setCustomerAddress($customerInfo['address'] ?? '');
        $sale->setObservations($customerInfo['observations'] ?? 'Pedido desde WhatsApp');
        
        $sale->setNotes(sprintf(
            "Producto: %s - Pedido automático desde WhatsApp - %s - %d pedido(s) de %d piezas",
            $item['name'],
            date('d/m/Y H:i'),
            $item['quantity'],
            $item['pieceCount'] ?? $item['quantity']
        ));

        return $sale;
    }
}