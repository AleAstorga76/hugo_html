<?php
// src/Controller/Admin/DashboardController.php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Sale;
use App\Entity\Cost;
use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Obtener estadísticas para el dashboard (manejar errores temporalmente)
        try {
            $salesStats = $this->getSalesStats();
            $costsStats = $this->getCostsStats();
            $profitStats = $this->getProfitStats();
            $recentSales = $this->getRecentSales();
            $recentCosts = $this->getRecentCosts();
            $pendingOrders = $this->getPendingOrdersCount();
        } catch (\Exception $e) {
            // Si hay errores (tablas no creadas), usar valores por defecto
            $salesStats = ['today' => 0, 'month' => 0, 'total' => 0, 'pending' => 0];
            $costsStats = ['month' => 0, 'total' => 0];
            $profitStats = ['month' => 0, 'total' => 0, 'margin' => 0];
            $recentSales = [];
            $recentCosts = [];
            $pendingOrders = 0;
        }

        return $this->render('admin/dashboard.html.twig', [
            'salesStats' => $salesStats,
            'costsStats' => $costsStats,
            'profitStats' => $profitStats,
            'recentSales' => $recentSales,
            'recentCosts' => $recentCosts,
            'pendingOrders' => $pendingOrders,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('🍣 Hugo Sushi Admin')
            ->setFaviconPath('favicon.ico')
            ->setTextDirection('ltr')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-chart-line');
        
        // Sección de Ventas
        yield MenuItem::section('💰 Gestión de Ventas');
        yield MenuItem::linkToCrud('Ventas', 'fas fa-cash-register', Sale::class);
        yield MenuItem::linkToCrud('Costos', 'fas fa-file-invoice-dollar', Cost::class);
        
        // Sección del Menú del Restaurante
        yield MenuItem::section('🍣 Gestión del Menú');
        yield MenuItem::linkToCrud('Productos', 'fas fa-utensils', Product::class);
        
        // Sección de Usuarios
        yield MenuItem::section('👥 Gestión de Usuarios');
        yield MenuItem::linkToCrud('Usuarios', 'fas fa-users', User::class);
        
        // Otros enlaces
        yield MenuItem::linkToUrl('🌐 Ver Sitio Web', 'fas fa-external-link-alt', '/');
        yield MenuItem::linkToLogout('🚪 Salir', 'fas fa-sign-out-alt');
    }

    private function getSalesStats(): array
    {
        $today = new \DateTime('today');
        $monthStart = new \DateTime('first day of this month');

        // SOLO ventas CONFIRMADAS para las estadísticas
        $todaySales = $this->entityManager->createQuery(
            'SELECT SUM(s.totalAmount) FROM App\Entity\Sale s 
             WHERE s.saleDate >= :today AND s.status = :status'
        )->setParameters([
            'today' => $today,
            'status' => Sale::STATUS_CONFIRMED
        ])->getSingleScalarResult() ?? 0;

        $monthSales = $this->entityManager->createQuery(
            'SELECT SUM(s.totalAmount) FROM App\Entity\Sale s 
             WHERE s.saleDate >= :monthStart AND s.status = :status'
        )->setParameters([
            'monthStart' => $monthStart,
            'status' => Sale::STATUS_CONFIRMED
        ])->getSingleScalarResult() ?? 0;

        $totalSales = $this->entityManager->createQuery(
            'SELECT COUNT(s.id) FROM App\Entity\Sale s WHERE s.status = :status'
        )->setParameter('status', Sale::STATUS_CONFIRMED)
         ->getSingleScalarResult();

        $pendingOrders = $this->entityManager->createQuery(
            'SELECT COUNT(s.id) FROM App\Entity\Sale s WHERE s.status = :status'
        )->setParameter('status', Sale::STATUS_PENDING)
         ->getSingleScalarResult();

        return [
            'today' => $todaySales,
            'month' => $monthSales,
            'total' => $totalSales,
            'pending' => $pendingOrders,
        ];
    }

    private function getCostsStats(): array
    {
        $monthStart = new \DateTime('first day of this month');

        $monthCosts = $this->entityManager->createQuery(
            'SELECT SUM(c.amount) FROM App\Entity\Cost c WHERE c.costDate >= :monthStart'
        )->setParameter('monthStart', $monthStart)
         ->getSingleScalarResult() ?? 0;

        $totalCosts = $this->entityManager->createQuery(
            'SELECT SUM(c.amount) FROM App\Entity\Cost c'
        )->getSingleScalarResult() ?? 0;

        return [
            'month' => $monthCosts,
            'total' => $totalCosts,
        ];
    }

    private function getProfitStats(): array
    {
        $monthSales = $this->getSalesStats()['month'];
        $monthCosts = $this->getCostsStats()['month'];
        $monthProfit = $monthSales - $monthCosts;

        $totalSales = $this->getSalesStats()['total'];
        $totalCosts = $this->getCostsStats()['total'];
        $totalProfit = $totalSales - $totalCosts;

        return [
            'month' => $monthProfit,
            'total' => $totalProfit,
            'margin' => $monthSales > 0 ? ($monthProfit / $monthSales) * 100 : 0,
        ];
    }

    private function getRecentSales(): array
    {
        // Mostrar TODAS las ventas recientes (incluyendo pendientes) para ver los nuevos pedidos
        return $this->entityManager->createQuery(
            'SELECT s FROM App\Entity\Sale s ORDER BY s.saleDate DESC'
        )->setMaxResults(5)
         ->getResult();
    }

    private function getRecentCosts(): array
    {
        return $this->entityManager->createQuery(
            'SELECT c FROM App\Entity\Cost c ORDER BY c.costDate DESC'
        )->setMaxResults(5)
         ->getResult();
    }

    private function getPendingOrdersCount(): int
    {
        return $this->entityManager->createQuery(
            'SELECT COUNT(s.id) FROM App\Entity\Sale s WHERE s.status = :status'
        )->setParameter('status', Sale::STATUS_PENDING)
         ->getSingleScalarResult();
    }
}