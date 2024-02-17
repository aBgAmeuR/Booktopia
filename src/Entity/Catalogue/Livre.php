<?php

namespace App\Entity\Catalogue;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Livre extends Article
{
    #[ORM\Column(length: 255,name: 'auteur')]
    private ?string $auteur = null;

    #[ORM\Column(length: 255, name: 'editeur')]
    private ?string $editeur = null;

    #[ORM\Column(length: 255, name: 'date_de_publication')]
    private ?string $dateDePublication = null;

    #[ORM\Column(length: 255, name: 'isbn')]
    private ?string $ISBN = null;

    #[ORM\Column(name: 'nb_pages')]
    private ?int $nbPages = null;

    #[ORM\Column(length: 255, name: 'resume')]
    private ?string $resume = null;

    #[ORM\Column(length: 255, name: 'categorie')]
    private ?string $categorie = null;


    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getEditeur(): ?string
    {
        return $this->editeur;
    }

    public function setEditeur(string $editeur): static
    {
        $this->editeur = $editeur;

        return $this;
    }

    public function getDateDePublication(): ?string
    {
        return $this->dateDePublication;
    }

    public function setDateDePublication(string $dateDePublication): static
    {
        $this->dateDePublication = $dateDePublication;

        return $this;
    }

    public function getISBN(): ?string
    {
        return $this->ISBN;
    }

    public function setISBN(string $ISBN): static
    {
        $this->ISBN = $ISBN;

        return $this;
    }

    public function getNbPages(): ?int
    {
        return $this->nbPages;
    }

    public function setNbPages(int $nbPages): static
    {
        $this->nbPages = $nbPages;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): static
    {
        $this->resume = $resume;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }
    
}

