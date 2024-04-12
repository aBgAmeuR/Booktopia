<?php

namespace App\Repository;

use App\Entity\Catalogue\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    // Ajoutez ici vos méthodes personnalisées pour le repository

    // Par exemple, une méthode pour récupérer tous les articles publiés
    public function findAllPublishedArticles()
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.published = :published')
            ->setParameter('published', true)
            ->getQuery()
            ->getResult();
    }
}
