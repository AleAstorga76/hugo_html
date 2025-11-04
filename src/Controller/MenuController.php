<?php
// src/Controller/MenuController.php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'app_menu')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Traemos los productos por categoría directamente
        $rolls = $entityManager->getRepository(Product::class)->findBy(['category' => 'rolls']);
        $combos = $entityManager->getRepository(Product::class)->findBy(['category' => 'combos']);
        $especialidades = $entityManager->getRepository(Product::class)->findBy(['category' => 'especialidades']);

        return $this->render('menu/index.html.twig', [
            'rolls' => $rolls,
            'combos' => $combos,
            'especialidades' => $especialidades,
        ]);
    }
}