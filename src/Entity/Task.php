<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $due_date = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Projects $project = null;

    /**
     * @var Collection<int, ProfilUser>
     */
    #[ORM\ManyToMany(targetEntity: ProfilUser::class, inversedBy: 'tasks')]
    private Collection $users;


    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDueDate(?\DateTimeInterface $due_date): static
    {
        $this->due_date = $due_date;

        return $this;
    }

    public function getProject(): ?Projects
    {
        return $this->project;
    }

    public function setProject(?Projects $project): static
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return Collection<int, ProfilUser>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(ProfilUser $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(ProfilUser $user): static
    {
        $this->users->removeElement($user);

        return $this;
    }

}
