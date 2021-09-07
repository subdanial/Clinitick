<?php

namespace App\Entity;

use App\Repository\ClinicDoctorsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClinicDoctorsRepository::class)
 */
class ClinicDoctors
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Clinics::class, inversedBy="clinicDoctors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $clinic;

    /**
     * @ORM\ManyToOne(targetEntity=Doctors::class, inversedBy="clinicDoctors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $doctor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClinic(): ?Clinics
    {
        return $this->clinic;
    }

    public function setClinic(?Clinics $clinic): self
    {
        $this->clinic = $clinic;

        return $this;
    }

    public function getDoctor(): ?Doctors
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctors $doctor): self
    {
        $this->doctor = $doctor;

        return $this;
    }
}
