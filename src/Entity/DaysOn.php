<?php

namespace App\Entity;

use App\Repository\DaysOnRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DaysOnRepository::class)]
class DaysOn
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $start_morning = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $end_morning = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $start_afternoon = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $end_afternoon = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStartMorning(): ?\DateTimeInterface
    {
        return $this->start_morning;
    }

    public function setStartMorning(\DateTimeInterface $start_morning): self
    {
        $this->start_morning = $start_morning;

        return $this;
    }

    public function getEndMorning(): ?\DateTimeInterface
    {
        return $this->end_morning;
    }

    public function setEndMorning(\DateTimeInterface $end_morning): self
    {
        $this->end_morning = $end_morning;

        return $this;
    }

    public function getStartAfternoon(): ?\DateTimeInterface
    {
        return $this->start_afternoon;
    }

    public function setStartAfternoon(\DateTimeInterface $start_afternoon): self
    {
        $this->start_afternoon = $start_afternoon;

        return $this;
    }

    public function getEndAfternoon(): ?\DateTimeInterface
    {
        return $this->end_afternoon;
    }

    public function setEndAfternoon(\DateTimeInterface $end_afternoon): self
    {
        $this->end_afternoon = $end_afternoon;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
