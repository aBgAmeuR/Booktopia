<?php

namespace App\Repository;

use App\DTO\SearchDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Catalogue\Livre;

class LivreRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Livre::class);
  }

  public function search(SearchDto $searchDto): array
  {
    $qb = $this->createQueryBuilder('l');

    if ($searchDto->search) {
      $qb->andWhere('l.titre LIKE :search')
        ->setParameter('search', '%' . $searchDto->search . '%');
    }
    if ($searchDto->category) {
      $qb->andWhere('l.categorie = :category')
        ->setParameter('category', $searchDto->category);
    }

    return $qb->getQuery()
      ->getResult();
  }
}