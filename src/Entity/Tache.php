<?php

namespace App\Entity;

use App\Repository\TacheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TacheRepository::class)]
class Tache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nameTask = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $deadLine = null;

    /**
     * @var Collection<int, Personne>
     */
    #[ORM\ManyToMany(targetEntity: Personne::class, mappedBy: 'taches')]
    private Collection $personnes;

    public function __construct()
    {
        $this->personnes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameTask(): ?string
    {
        return $this->nameTask;
    }

    public function setNameTask(?string $nameTask): static
    {
        $this->nameTask = $nameTask;

        return $this;
    }

    public function getDeadLine(): ?string
    {
        return $this->deadLine;
    }

    public function setDeadLine(?string $deadLine): static
    {
        $this->deadLine = $deadLine;

        return $this;
    }

    /**
     * @return Collection<int, Personne>
     */
    public function getPersonnes(): Collection
    {
        return $this->personnes;
    }

    public function addPersonne(Personne $personne): static
    {
        if (!$this->personnes->contains($personne)) {
            $this->personnes->add($personne);
            $personne->addTach($this);
        }

        return $this;
    }

    public function removePersonne(Personne $personne): static
    {
        if ($this->personnes->removeElement($personne)) {
            $personne->removeTach($this);
        }

        return $this;
    }
}
