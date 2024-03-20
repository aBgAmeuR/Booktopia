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

    /**
     * Find a wishlist by the associated user's ID.
     *
     * @param int $userId
     * @return Wishlist|null
     */
    public function findByUserId(int $userId): ?Wishlist
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.utilisateur = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find wishlists that contain a specific article.
     *
     * @param int $articleId
     * @return Wishlist[]
     */
    public function findByArticleId(int $articleId): array
    {
        return $this->createQueryBuilder('w')
            ->innerJoin('w.articles', 'a')
            ->where('a.id = :articleId')
            ->setParameter('articleId', $articleId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find wishlists with more than a certain number of articles.
     *
     * @param int $count
     * @return Wishlist[]
     */
    public function findByArticleCountGreaterThan(int $count): array
    {
        // This is a bit more complex and might need custom DQL or a native query,
        // since counting related entities directly in a query builder might not be straightforward.
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT w
             FROM App\Entity\Wishlist w
             JOIN w.articles a
             GROUP BY w.id
             HAVING COUNT(a.id) > :count'
        )->setParameter('count', $count);

        return $query->getResult();
    }
}
