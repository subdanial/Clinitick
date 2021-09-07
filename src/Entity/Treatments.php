<?php

namespace App\Entity;

use App\Repository\TreatmentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TreatmentsRepository::class)
 */
class Treatments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Appointments::class, inversedBy="treatments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $appointment;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $teeth_number;

    /**
     * @ORM\Column(type="integer")
     */
    private $total_price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $plan_name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppointment(): ?Appointments
    {
        return $this->appointment;
    }

    public function setAppointment(?Appointments $appointment): self
    {
        $this->appointment = $appointment;

        return $this;
    }

    public function getTeethNumber(): ?string
    {
        return $this->teeth_number;
    }

    public function setTeethNumber(string $teeth_number): self
    {
        $this->teeth_number = $teeth_number;

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->total_price;
    }

    public function setTotalPrice(int $total_price): self
    {
        $this->total_price = $total_price;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->create_date;
    }

    public function setCreateDate(\DateTimeInterface $create_date): self
    {
        $this->create_date = $create_date;

        return $this;
    }

    public function getPlanName(): ?string
    {
        return $this->plan_name;
    }

    public function setPlanName(string $plan_name): self
    {
        $this->plan_name = $plan_name;

        return $this;
    }
}
