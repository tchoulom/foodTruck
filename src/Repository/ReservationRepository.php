<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * Vérifie si un food truck a déjà réservé pour la semaine en cours.
     */
    public function hasFoodTruckBookedThisWeek(string $foodTruckName): bool
    {
        $startOfWeek = new \DateTimeImmutable('monday this week');
        $endOfWeek = new \DateTimeImmutable('sunday this week');

        $query = $this->createQueryBuilder('r')
            ->where('r.foodTruckName = :name')
            ->andWhere('r.date BETWEEN :start AND :end')
            ->setParameter('name', $foodTruckName)
            ->setParameter('start', $startOfWeek)
            ->setParameter('end', $endOfWeek)
            ->getQuery();

        return count($query->getResult()) > 0;
    }
}
