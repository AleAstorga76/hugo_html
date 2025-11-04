<?php
// src/Controller/Api/OrderController.php

namespace App\Controller\Api;

use App\Service\OrderProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/api/save-order', name: 'api_save_order', methods: ['POST'])]
    public function saveOrder(Request $request, OrderProcessor $orderProcessor): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        try {
            $sales = $orderProcessor->processWhatsAppOrder(
                $data['cart'] ?? [],
                $data['customer'] ?? []
            );
            
            return $this->json([
                'success' => true,
                'message' => 'Pedido guardado correctamente',
                'sales_count' => count($sales)
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Error guardando el pedido: ' . $e->getMessage()
            ], 500);
        }
    }
}