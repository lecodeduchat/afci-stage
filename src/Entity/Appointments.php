<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AppointmentsRepository;

#[ORM\Entity(repositoryClass: AppointmentsRepository::class)]
class Appointments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cares $care = null;

    #[ORM\Column(nullable: false)]
    private ?int $user_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(nullable: true)]
    private ?int $child_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCare(): ?Cares
    {
        return $this->care;
    }

    public function setCare(Cares $care): self
    {
        $this->care = $care;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getChildId(): ?int
    {
        return $this->child_id;
    }

    public function setChildId(?int $child_id): self
    {
        $this->child_id = $child_id;

        return $this;
    }
}
