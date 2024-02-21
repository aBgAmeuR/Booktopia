<?php

namespace App\Entity;

use App\Entity\Catalogue\Article;
use App\Repository\WishlistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WishlistRepository::class)]
class Wishlist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Assuming a OneToOne relationship with Utilisateur for simplicity.
    // This could be adjusted based on your application's requirements.
    #[ORM\OneToOne(targetEntity: Utilisateur::class, inversedBy: 'wishlist')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToMany(targetEntity: Article::class)]
    #[ORM\JoinTable(name: "wishlist_articles")]
    private Collection $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
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
        $this->utilisateur = $utilisateur;
        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
        }
        return $this;
    }

    public function removeArticle(Article $article): self
    {
        $this->articles->removeElement($article);
        return $this;
    }
}
