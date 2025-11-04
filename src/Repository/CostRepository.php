<?php
// src/Repository/CostRepository.php

namespace App\Repository;

use App\Entity\Cost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cost>
 */
class CostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cost::class);
    }

    // Ejemplo de método personalizado si lo necesitas
    public function findMonthlyCosts(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.costDate BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->orderBy('c.costDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}