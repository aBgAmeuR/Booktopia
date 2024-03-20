<?php

namespace App\Repository;

use App\Entity\Utilisateur\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Role::class);
    }

    /**
     * Find roles by a specific name.
     *
     * @param string $name
     * @return Role[]
     */
    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find roles with a specific permission or attribute.
     * 
     * Note: This assumes your Role entity might have additional fields or relations
     * that denote permissions or attributes. Adjust the method accordingly.
     *
     * @param string $permission
     * @return Role[]
     */
    public function findByPermission(string $permission): array
    {
        // Example assumes a 'permissions' field or related entity. Adjust as needed.
        return $this->createQueryBuilder('r')
            ->join('r.permissions', 'p')
            ->where('p.name = :permission')
            ->setParameter('permission', $permission)
            ->getQuery()
            ->getResult();
    }

    /**
     * List all users associated with a certain role.
     *
     * @param int $roleId
     * @return User[]
     */
    public function findUsersByRoleId(int $roleId): array
    {
        return $this->createQueryBuilder('r')
            ->select('u')
            ->join('r.utilisateurs', 'u')
            ->where('r.id = :roleId')
            ->setParameter('roleId', $roleId)
            ->getQuery()
            ->getResult();
    }
}
