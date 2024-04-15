<?php

namespace App\Repository;

use App\Entity\Utilisateur\Wishlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WishlistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wishlist::class);
    }

    public function getWishlistOfCurrentUser(): array
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.utilisateur = :user')
            ->setParameter('user', $this->getEntityManager()->getUser())
            ->getQuery()
            ->getResult();
    }

    public function isArticleInWishlist($articleId, $userId): bool
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.utilisateur = :user')
            ->setParameter('user', $userId)
            ->andWhere(':article MEMBER OF w.articles')
            ->setParameter('article', $articleId)
            ->getQuery()
            ->getOneOrNullResult() !== null;
    }
}