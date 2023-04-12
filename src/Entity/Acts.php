<?php

namespace App\Entity;

use App\Repository\ActsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActsRepository::class)]
class Acts
{
    #[ORM\Id]
    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Invoices $invoice = null;

    #[ORM\Id]
    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Appointments $appointment = null;

    public function getInvoice(): ?Invoices
    {
        return $this->invoice;
    }

    public function setInvoice(Invoices $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getAppointment(): ?Appointments
    {
        return $this->appointment;
    }

    public function setAppointment(Appointments $appointment): self
    {
        $this->appointment = $appointment;

        return $this;
    }
}
