<?php

namespace App\Repository;

use App\Entity\Utilisateur\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /**
     * Find orders by status.
     *
     * @param string $status
     * @return Commande[]
     */
    public function findByStatus(string $status): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find orders within a certain date range.
     *
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @return Commande[]
     */
    public function findByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.date BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find orders with a total amount exceeding a certain threshold.
     *
     * @param float $amount
     * @return Commande[]
     */
    public function findByAmountGreaterThan(float $amount): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.total > :amount')
            ->setParameter('amount', $amount)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find orders for a specific user.
     *
     * @param int $userId
     * @return Commande[]
     */
    public function findByUserId(int $userId): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.utilisateur', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    // You can add more custom methods as needed...
}
