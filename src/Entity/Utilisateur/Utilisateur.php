<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Commande::class)]
    private Collection $commandes;

    #[ORM\ManyToMany(targetEntity: Role::class, mappedBy: 'utilisateurs')]
    private Collection $roles;

    #[ORM\OneToOne(mappedBy: 'utilisateur', targetEntity: Wishlist::class, cascade: ['persist', 'remove'])]
    private ?Wishlist $wishlist = null;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    // Commandes
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setUtilisateur($this);
        }
        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getUtilisateur() === $this) {
                $commande->setUtilisateur(null);
            }
        }
        return $this;
    }

    // Roles
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->addUtilisateur($this);
        }
        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->roles->removeElement($role)) {
            $role->removeUtilisateur($this);
        }
        return $this;
    }

    // Wishlist
    public function getWishlist(): ?Wishlist
    {
        return $this->wishlist;
    }

    public function setWishlist(?Wishlist $wishlist): self
    {
        // unset the owning side of the relation if necessary
        if ($wishlist === null && $this->wishlist !== null) {
            $this->wishlist->setUtilisateur(null);
        }

        // set the owning side of the relation if necessary
        if ($wishlist !== null && $wishlist->getUtilisateur() !== $this) {
            $wishlist->setUtilisateur($this);
        }

        $this->wishlist = $wishlist;
        return $this;
    }
}
