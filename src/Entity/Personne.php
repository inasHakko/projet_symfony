<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
    #[ORM\HasLifecycleCallbacks()]

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
class Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min:3,
        max:50,
        minMessage:"Le prénom d'utilisateur doit contenir au moins {{ limit }} caractères.",
        maxMessage:"Le prénom d'utilisateur ne peut pas contenir plus de {{ limit }} caractères."
        )]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min:3,
        max:50,
        minMessage:"Le nom d'utilisateur doit contenir au moins {{ limit }} caractères.",
        maxMessage:"Le nom d'utilisateur ne peut pas contenir plus de {{ limit }} caractères."
        )]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "L'âge est obligatoire.")]
    #[Assert\Positive(message: "L'âge doit être un nombre positif.")]
    #[Assert\Range(
        min: 18,
        max: 99,
        notInRangeMessage: "L'âge doit être compris entre {{ min }} et {{ max }} ans."
    )]
    private ?int $age = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min:5,
        max:50,
        minMessage:"Le job d'utilisateur doit contenir au moins {{ limit }} caractères.",
        maxMessage:"Le job d'utilisateur ne peut pas contenir plus de {{ limit }} caractères."
        )]
    private ?string $job = null;

    #[ORM\OneToOne(inversedBy: 'personne', cascade: ['persist', 'remove'])]
    private ?Profile $profile = null;

    /**
     * @var Collection<int, Tache>
     */
    #[ORM\ManyToMany(targetEntity: Tache::class, inversedBy: 'personnes')]
    private Collection $taches;

    /**
     * @var Collection<int, competence>
     */
    #[ORM\ManyToMany(targetEntity: Competence::class, inversedBy: 'personnes')]
    private Collection $competences;

    // #[ORM\Column(nullable: true)]
    // private ?\DateTimeImmutable $createdAt = null;

    // #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    // private ?\DateTimeInterface $updatedAt = null;
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'personnes')]
    private ?User $createdBy = null;

    public function __construct()
    {
        $this->taches = new ArrayCollection();
        $this->competences = new ArrayCollection();
        // $this->createdAt = new \DateTimeImmutable();
        // $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * @return Collection<int, Tache>
     */
    public function getTaches(): Collection
    {
        return $this->taches;
    }

    public function addTach(Tache $tach): static
    {
        if (!$this->taches->contains($tach)) {
            $this->taches->add($tach);
        }

        return $this;
    }

    public function removeTach(Tache $tach): static
    {
        $this->taches->removeElement($tach);

        return $this;
    }

    /**
     * @return Collection<int, competence>
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(competence $competence): static
    {
        if (!$this->competences->contains($competence)) {
            $this->competences->add($competence);
        }

        return $this;
    }

    public function removeCompetence(competence $competence): static
    {
        $this->competences->removeElement($competence);

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
     * @ORM\PrePersist()
     */
    #[ORM\PrePersist()]
    public function onPrePersist(): void
    {
        // dump('onPrePersist called');
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        // dump('onPreUpdate called');
        $this->updatedAt = new \DateTime();
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}
