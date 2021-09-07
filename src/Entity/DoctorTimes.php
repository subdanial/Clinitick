<?php

namespace App\Entity;

use App\Repository\DoctorTimesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DoctorTimesRepository::class)
 */
class DoctorTimes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Doctors::class, inversedBy="doctorTimes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $doctor;

    /**
     * @ORM\Column(type="integer")
     */
    private $day_of_week;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $from_time;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $to_time;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDayOfWeek(): ?int
    {
        return $this->day_of_week;
    }

    public function setDayOfWeek(int $day_of_week): self
    {
        $this->day_of_week = $day_of_week;

        return $this;
    }

    public function getFromTime(): ?\DateTimeInterface
    {
        return $this->from_time;
    }

    public function setFromTime(?\DateTimeInterface $from_time): self
    {
        $this->from_time = $from_time;

        return $this;
    }

    public function getToTime(): ?\DateTimeInterface
    {
        return $this->to_time;
    }

    public function setToTime(?\DateTimeInterface $to_time): self
    {
        $this->to_time = $to_time;

        return $this;
    }
}
