<?php

namespace App\Entity;

use App\Repository\ClinicAssistantsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClinicAssistantsRepository::class)
 */
class ClinicAssistants
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Clinics::class, inversedBy="clinicAssistants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $clinic;

    /**
     * @ORM\ManyToOne(targetEntity=Assistants::class, inversedBy="clinicAssistants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $assistant;

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

    public function getAssistant(): ?Assistants
    {
        return $this->assistant;
    }

    public function setAssistant(?Assistants $assistant): self
    {
        $this->assistant = $assistant;

        return $this;
    }
}
