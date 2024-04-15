<?php

namespace App\Entity\Utilisateur;

use App\Entity\Panier\LignePanier;
use App\Entity\Utilisateur\Utilisateur;
use App\Entity\Utilisateur\Adresse;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;


    #[ORM\Column]
    private ?string $status = null;

    #[ORM\Column(type: 'float')]
    private ?float $total = null;

    #[ORM\ManyToOne(targetEntity: Adresse::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adresse $adresse = null;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $date = null;

    // Ajoutez cette propriété
    /**
     * @var Collection|LignePanier[]
     */
    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: LignePanier::class, cascade: ['persist', 'remove'])]
    private Collection $lineItems;

    public function __construct()
    {
        $this->lineItems = new ArrayCollection();
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        if ($utilisateur === null) {
            throw new \InvalidArgumentException('Utilisateur cannot be null');
        }

        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;
        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresse $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return Collection|LignePanier[]
     */
    public function getLineItems(): Collection
    {
        return $this->lineItems;
    }


    public function addLineItem(LignePanier $lineItem): self
    {
        if (!$this->lineItems->contains($lineItem)) {
            $this->lineItems[] = $lineItem;
            $lineItem->setCommande($this);
        }

        return $this;
    }

    public function removeLineItem(LignePanier $lineItem): self
    {
        if ($this->lineItems->removeElement($lineItem)) {
            // set the owning side to null (unless already changed)
            if ($lineItem->getCommande() === $this) {
                $lineItem->setCommande(null);
            }
        }
        return $this;
    }
}
