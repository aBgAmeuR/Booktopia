<?php

namespace App\Repository;

use App\Entity\Utilisateur\Adresse;
use App\Entity\Utilisateur\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AdresseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adresse::class);
    }

    /**
     * Find addresses by user.
     *
     * @param Utilisateur $utilisateur
     * @return Adresse[]
     */
    public function findByUtilisateur(Utilisateur $utilisateur): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.utilisateur = :utilisateur')
            ->setParameter('utilisateur', $utilisateur)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find addresses by city.
     *
     * @param string $city
     * @return Adresse[]
     */
    public function findByCity(string $city): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.city = :city')
            ->setParameter('city', $city)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find addresses by country.
     *
     * @param string $country
     * @return Adresse[]
     */
    public function findByCountry(string $country): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.country = :country')
            ->setParameter('country', $country)
            ->getQuery()
            ->getResult();
    }

    // Example: Update an address detail (e.g., postal code) by address ID
    public function updatePostalCode(int $adresseId, string $newPostalCode): int
    {
        return $this->createQueryBuilder('a')
            ->update()
            ->set('a.postalCode', ':newPostalCode')
            ->where('a.id = :id')
            ->setParameter('newPostalCode', $newPostalCode)
            ->setParameter('id', $adresseId)
            ->getQuery()
            ->execute();
    }

    // Add more custom methods as needed...
}
