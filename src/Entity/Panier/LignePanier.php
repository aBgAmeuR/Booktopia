<?php

namespace App\Entity\Panier;

use App\Entity\Catalogue\Article;

class LignePanier
{
    private Article $article;

    private float $prixUnitaire;

    private float $prixTotal;

    private int $quantite;
	
	public function __construct()
    {
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
        $this->prixTotal = $this->quantite * $this->prixUnitaire ;
    }
}

