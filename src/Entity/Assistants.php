<?php

namespace App\Entity;

use App\Repository\AssistantsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=AssistantsRepository::class)
 */
class Assistants implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=11)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_date;

    /**
     * @ORM\OneToMany(targetEntity=Appointments::class, mappedBy="status_changed_by_assistant")
     */
    private $appointments;

    /**
     * @ORM\OneToMany(targetEntity=Reminders::class, mappedBy="assistant")
     */
    private $reminders;

    /**
     * @ORM\OneToMany(targetEntity=ReminderLists::class, mappedBy="assistant")
     */
    private $reminderLists;

    /**
     * @ORM\OneToMany(targetEntity=ClinicAssistants::class, mappedBy="assistant")
     */
    private $clinicAssistants;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="text")
     */
    private $plain_password;

    public function __construct()
    {
        $this->appointments = new ArrayCollection();
        $this->reminders = new ArrayCollection();
        $this->reminderLists = new ArrayCollection();
        $this->clinicAssistants = new ArrayCollection();
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

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

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
            $appointment->setStatusChangedByAssistant($this);
        }

        return $this;
    }

    public function removeAppointment(Appointments $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getStatusChangedByAssistant() === $this) {
                $appointment->setStatusChangedByAssistant(null);
            }
        }

        return $this;
    }

    public function getUsername(): string
    {
        return $this->getMobile();
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|ReminderLists[]
     */
    public function getReminderLists(): Collection
    {
        return $this->reminderLists;
    }

    public function addReminderList(ReminderLists $reminderList): self
    {
        if (!$this->reminderLists->contains($reminderList)) {
            $this->reminderLists[] = $reminderList;
            $reminderList->setAssistant($this);
        }

        return $this;
    }

    public function removeReminderList(ReminderLists $reminderList): self
    {
        if ($this->reminderLists->removeElement($reminderList)) {
            // set the owning side to null (unless already changed)
            if ($reminderList->getAssistant() === $this) {
                $reminderList->setAssistant(null);
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
            $clinicAssistant->setAssistant($this);
        }

        return $this;
    }

    public function removeClinicAssistant(ClinicAssistants $clinicAssistant): self
    {
        if ($this->clinicAssistants->removeElement($clinicAssistant)) {
            // set the owning side to null (unless already changed)
            if ($clinicAssistant->getAssistant() === $this) {
                $clinicAssistant->setAssistant(null);
            }
        }

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plain_password;
    }

    public function setPlainPassword(string $plain_password): self
    {
        $this->plain_password = $plain_password;

        return $this;
    }

    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }
}
