<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nameRS = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Personne = null;

    #[ORM\OneToOne(mappedBy: 'profile', cascade: ['persist', 'remove'])]
    private ?Personne $personne = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getNameRS(): ?string
    {
        return $this->nameRS;
    }

    public function setNameRS(?string $nameRS): static
    {
        $this->nameRS = $nameRS;

        return $this;
    }

    public function getPersonne(): ?string
    {
        return $this->Personne;
    }

    public function setPersonne(?string $Personne): static
    {
        $this->Personne = $Personne;

        return $this;
    }
}
