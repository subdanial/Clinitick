<?php

namespace App\Entity;

use App\Repository\DoctorsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DoctorsRepository::class)
 */
class Doctors
{
    /**
     * @ORM\Id
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
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $whatsapp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telegram;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $google_map;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $waze;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $update_date;

    /**
     * @ORM\OneToMany(targetEntity=Appointments::class, mappedBy="doctor")
     */
    private $appointments;

    /**
     * @ORM\OneToMany(targetEntity=DoctorTimes::class, mappedBy="doctor")
     */
    private $doctorTimes;

    /**
     * @ORM\OneToMany(targetEntity=ClinicDoctors::class, mappedBy="doctor")
     */
    private $clinicDoctors;

    /**
     * @ORM\OneToMany(targetEntity=DoctorCustomers::class, mappedBy="doctor")
     */
    private $doctorCustomers;

    public function __construct()
    {
        $this->appointments = new ArrayCollection();
        $this->doctorTimes = new ArrayCollection();
        $this->clinicDoctors = new ArrayCollection();
        $this->doctorCustomers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getWhatsapp(): ?string
    {
        return $this->whatsapp;
    }

    public function setWhatsapp(?string $whatsapp): self
    {
        $this->whatsapp = $whatsapp;

        return $this;
    }

    public function getTelegram(): ?string
    {
        return $this->telegram;
    }

    public function setTelegram(?string $telegram): self
    {
        $this->telegram = $telegram;

        return $this;
    }

    public function getGoogleMap(): ?string
    {
        return $this->google_map;
    }

    public function setGoogleMap(?string $google_map): self
    {
        $this->google_map = $google_map;

        return $this;
    }

    public function getWaze(): ?string
    {
        return $this->waze;
    }

    public function setWaze(?string $waze): self
    {
        $this->waze = $waze;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->update_date;
    }

    public function setUpdateDate(\DateTimeInterface $update_date): self
    {
        $this->update_date = $update_date;

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
            $appointment->setDoctor($this);
        }

        return $this;
    }

    public function removeAppointment(Appointments $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getDoctor() === $this) {
                $appointment->setDoctor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DoctorTimes[]
     */
    public function getDoctorTimes(): Collection
    {
        return $this->doctorTimes;
    }

    public function addDoctorTime(DoctorTimes $doctorTime): self
    {
        if (!$this->doctorTimes->contains($doctorTime)) {
            $this->doctorTimes[] = $doctorTime;
            $doctorTime->setDoctor($this);
        }

        return $this;
    }

    public function removeDoctorTime(DoctorTimes $doctorTime): self
    {
        if ($this->doctorTimes->removeElement($doctorTime)) {
            // set the owning side to null (unless already changed)
            if ($doctorTime->getDoctor() === $this) {
                $doctorTime->setDoctor(null);
            }
        }

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
            $clinicUser->setDoctor($this);
        }

        return $this;
    }

    public function removeClinicUser(ClinicDoctors $clinicDoctors): self
    {
        if ($this->clinicDoctors->removeElement($clinicDoctors)) {
            // set the owning side to null (unless already changed)
            if ($clinicUser->getDoctor() === $this) {
                $clinicUser->setDoctor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DoctorCustomers[]
     */
    public function getDoctorCustomers(): Collection
    {
        return $this->doctorCustomers;
    }

    public function addDoctorCustomer(DoctorCustomers $doctorCustomer): self
    {
        if (!$this->doctorCustomers->contains($doctorCustomer)) {
            $this->doctorCustomers[] = $doctorCustomer;
            $doctorCustomer->setDoctor($this);
        }

        return $this;
    }

    public function removeDoctorCustomer(DoctorCustomers $doctorCustomer): self
    {
        if ($this->doctorCustomers->removeElement($doctorCustomer)) {
            // set the owning side to null (unless already changed)
            if ($doctorCustomer->getDoctor() === $this) {
                $doctorCustomer->setDoctor(null);
            }
        }

        return $this;
    }
}
