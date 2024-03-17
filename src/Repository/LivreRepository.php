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

  public function getById(string $id): ?Livre
  {
    return $this->find($id);
  }

  public function getAll(): array
  {
    return $this->findAll();
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

  public function getLikeArticles(Livre $livre): array
  {
    return $this->createQueryBuilder('l')
      ->andWhere('l.categorie = :category')
      ->setParameter('category', $livre->getCategorie())
      ->andWhere('l.id != :id')
      ->setParameter('id', $livre->getId())
      ->setMaxResults(2)
      ->getQuery()
      ->getResult();
  }

  public function getCategories(int $limit = null): array
  {
    $query = $this->createQueryBuilder('l')
      ->select('l.categorie')
      ->distinct()
      ->addSelect('COUNT(l.categorie) as HIDDEN cnt')
      ->groupBy('l.categorie')
      ->orderBy('cnt', 'DESC');

    if ($limit) {
      $query->setMaxResults($limit);
    }

    return $query->getQuery()
      ->getResult();
  }

  public function getFeaturedProducts(int $limit = null): array
  {
    $query = $this->createQueryBuilder('l')
      ->select('l.id, l.titre, l.prix, l.auteur, l.image')
      ->distinct()
      ->addSelect('COUNT(l.id) as HIDDEN cnt')
      ->groupBy('l.id, l.titre, l.prix, l.image')
      ->orderBy('cnt', 'DESC');

    if ($limit) {
      $query->setMaxResults($limit);
    }

    return $query->getQuery()
      ->getResult();

  }
}