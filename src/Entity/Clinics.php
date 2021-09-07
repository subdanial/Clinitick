<?php

namespace App\Entity;

use App\Repository\ClinicsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClinicsRepository::class)
 */
class Clinics
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity=ClinicDoctors::class, mappedBy="clinic")
     */
    private $clinicDoctors;

    /**
     * @ORM\OneToMany(targetEntity=ClinicAssistants::class, mappedBy="clinic")
     */
    private $clinicAssistants;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $uid;

    /**
     * @ORM\OneToMany(targetEntity=Appointments::class, mappedBy="clinic")
     */
    private $appointments;

    public function __construct()
    {
        $this->clinicDoctors = new ArrayCollection();
        $this->clinicAssistants = new ArrayCollection();
        $this->appointments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection|ClinicDoctors[]
     */
    public function getClinicDoctors(): Collection
    {
        return $this->clinicDoctors;
    }

    public function addClinicUser(ClinicDoctors $clinicUser): self
    {
        if (!$this->clinicDoctors->contains($clinicUser)) {
            $this->clinicDoctors[] = $clinicUser;
            $clinicUser->setClinic($this);
        }

        return $this;
    }

    public function removeClinicUser(ClinicDoctors $clinicUser): self
    {
        if ($this->clinicDoctors->removeElement($clinicUser)) {
            // set the owning side to null (unless already changed)
            if ($clinicUser->getClinic() === $this) {
                $clinicUser->setClinic(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ClinicAssistants[]
     */
    public function getClinicAssistants(): Collection
    {
        return $this->clinicAssistants;
    }

    public function addClinicAssistant(ClinicAssistants $clinicAssistant): self
    {
        if (!$this->clinicAssistants->contains($clinicAssistant)) {
            $this->clinicAssistants[] = $clinicAssistant;
            $clinicAssistant->setClinic($this);
        }

        return $this;
    }

    public function removeClinicAssistant(ClinicAssistants $clinicAssistant): self
    {
        if ($this->clinicAssistants->removeElement($clinicAssistant)) {
            // set the owning side to null (unless already changed)
            if ($clinicAssistant->getClinic() === $this) {
                $clinicAssistant->setClinic(null);
            }
        }

        return $this;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(?string $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * @return Collection|Appointments[]
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointments $appointment): self
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments[] = $appointment;
            $appointment->setClinic($this);
        }

        return $this;
    }

    public function removeAppointment(Appointments $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getClinic() === $this) {
                $appointment->setClinic(null);
            }
        }

        return $this;
    }
}
