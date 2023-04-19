<?php

namespace App\Entity;

use App\Repository\SchedulesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SchedulesRepository::class)]
class Schedules
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $day = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $morning_start = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $morning_end = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $afternoon_start = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $afternoon_end = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getMorningStart(): ?\DateTimeInterface
    {
        return $this->morning_start;
    }

    public function setMorningStart(\DateTimeInterface $morning_start): self
    {
        $this->morning_start = $morning_start;

        return $this;
    }

    public function getMorningEnd(): ?\DateTimeInterface
    {
        return $this->morning_end;
    }

    public function setMorningEnd(\DateTimeInterface $morning_end): self
    {
        $this->morning_end = $morning_end;

        return $this;
    }

    public function getAfternoonStart(): ?\DateTimeInterface
    {
        return $this->afternoon_start;
    }

    public function setAfternoonStart(\DateTimeInterface $afternoon_start): self
    {
        $this->afternoon_start = $afternoon_start;

        return $this;
    }

    public function getAfternoonEnd(): ?\DateTimeInterface
    {
        return $this->afternoon_end;
    }

    public function setAfternoonEnd(\DateTimeInterface $afternoon_end): self
    {
        $this->afternoon_end = $afternoon_end;

        return $this;
    }
}
