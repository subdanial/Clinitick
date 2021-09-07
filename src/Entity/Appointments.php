<?php

namespace App\Entity;

use App\Repository\AppointmentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AppointmentsRepository::class)
 *
 * status => {
 *  0 => submitted but no action done
 *  1 => accepted
 *  2 => canceled,
 *  3 => online_order
 * }
 */
class Appointments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Customers::class, inversedBy="appointments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity=Doctors::class, inversedBy="appointments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $doctor;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Assistants::class, inversedBy="appointments")
     */
    private $status_changed_by_assistant;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_date;

    /**
     * @ORM\Column(type="date")
     */
    private $due_date;

    /**
     * @ORM\Column(type="time")
     */
    private $from_time;

    /**
     * @ORM\Column(type="time")
     */
    private $to_time;

    /**
     * @ORM\OneToMany(targetEntity=Treatments::class, mappedBy="appointment")
     */
    private $treatments;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $uid;

    /**
     * @ORM\ManyToOne(targetEntity=Clinics::class, inversedBy="appointments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $clinic;

    public function __construct()
    {
        $this->treatments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customers
    {
        return $this->customer;
    }

    public function setCustomer(?Customers $customer): self
    {
        $this->customer = $customer;

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatusChangedByAssistant(): ?Assistants
    {
        return $this->status_changed_by_assistant;
    }

    public function setStatusChangedByAssistant(?Assistants $status_changed_by_assistant): self
    {
        $this->status_changed_by_assistant = $status_changed_by_assistant;

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

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDueDate(\DateTimeInterface $due_date): self
    {
        $this->due_date = $due_date;

        return $this;
    }

    public function getFromTime(): ?\DateTimeInterface
    {
        return $this->from_time;
    }

    public function setFromTime(\DateTimeInterface $from_time): self
    {
        $this->from_time = $from_time;

        return $this;
    }

    public function getToTime(): ?\DateTimeInterface
    {
        return $this->to_time;
    }

    public function setToTime(\DateTimeInterface $to_time): self
    {
        $this->to_time = $to_time;

        return $this;
    }

    /**
     * @return Collection|Treatments[]
     */
    public function getTreatments(): Collection
    {
        return $this->treatments;
    }

    public function addTreatment(Treatments $treatment): self
    {
        if (!$this->treatments->contains($treatment)) {
            $this->treatments[] = $treatment;
            $treatment->setAppointment($this);
        }

        return $this;
    }

    public function removeTreatment(Treatments $treatment): self
    {
        if ($this->treatments->removeElement($treatment)) {
            // set the owning side to null (unless already changed)
            if ($treatment->getAppointment() === $this) {
                $treatment->setAppointment(null);
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

    public function getClinic(): ?Clinics
    {
        return $this->clinic;
    }

    public function setClinic(?Clinics $clinic): self
    {
        $this->clinic = $clinic;

        return $this;
    }
}
