<?php

namespace App\Entity\Catalogue;

use JsonSerializable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Piste implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, name: 'titre')]
    private ?string $titre = null;
	
    #[ORM\Column(length: 255, name: 'mp3')]
    private ?string $mp3 = null;
	
	#[ORM\ManyToOne(targetEntity: "Musique", cascade: ["persist"])]
    private Musique $musique;

    public function getId(): ?int
    {
        return $this->id;
    }
	
    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }
	
    public function getMp3(): ?string
    {
        return $this->mp3;
    }

    public function setMp3(string $mp3): static
    {
        $this->mp3 = $mp3;

        return $this;
    }

    public function getMusique(): Musique
    {
        return $this->musique;
    }
	
    public function setMusique(Musique $musique): static
    {
        $this->musique = $musique;

        return $this;
    }

	public function jsonSerialize(): array
    {
        return array(
            "titre" => $this->getTitre(),
			"mp3" => $this->getMp3()
        );
    }
}

