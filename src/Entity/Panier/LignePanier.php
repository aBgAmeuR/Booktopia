<?php

namespace App\Entity\Panier;

use App\Entity\Catalogue\Article;
use App\Entity\Utilisateur\Commande;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class LignePanier
{
    #[ORM\Id] // Marque la propriété suivante comme identifiant de l'entité
    #[ORM\GeneratedValue] // Configure la génération automatique de la valeur
    #[ORM\Column(type: 'integer')] // Définit le type de la colonne
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Article::class)] // Configure une relation ManyToOne avec l'entité Article

    private Article $article;
    #[ORM\Column(type: 'float')]


    private float $prixUnitaire;
    #[ORM\Column(type: 'float')]

    private float $prixTotal;
    #[ORM\Column(type: 'integer')]
    private int $quantite;
    #[ORM\ManyToOne(targetEntity: Commande::class)] // Configure une relation ManyToOne avec l'entité Commande

    private   ?Commande $commande = null;



    public function __construct()
    {
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setArticle(Article $article): LignePanier
    {
        $this->article = $article;
        $this->prixUnitaire = $article->getPrix();
        return $this;
    }

    public function getArticle(): Article
    {
        return $this->article;
    }

    public function setPrixUnitaire(float $prixUnitaire): LignePanier
    {
        $this->prixUnitaire = $prixUnitaire;
        $this->recalculer();
        return $this;
    }

    public function getPrixUnitaire(): float
    {
        return $this->prixUnitaire;
    }

    public function setPrixTotal(float $prixTotal): LignePanier
    {
        $this->prixTotal = $prixTotal;
        return $this;
    }

    public function getPrixTotal(): ?float
    {
        return $this->prixTotal;
    }

    public function setQuantite(int $quantite): LignePanier
    {
        $this->quantite = $quantite;
        $this->recalculer();
        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function recalculer()
    {
        $this->prixTotal = $this->quantite * $this->prixUnitaire;
    }
}
