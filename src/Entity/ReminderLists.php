<?php

namespace App\Entity;

use App\Repository\ReminderListsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReminderListsRepository::class)
 */
class ReminderLists
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Assistants::class, inversedBy="reminderLists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $assistant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_date;

    /**
     * @ORM\OneToMany(targetEntity=Reminders::class, mappedBy="reminder_list")
     */
    private $reminders;

    public function __construct()
    {
        $this->reminders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return Collection|Reminders[]
     */
    public function getReminders(): Collection
    {
        return $this->reminders;
    }

    public function addReminder(Reminders $reminder): self
    {
        if (!$this->reminders->contains($reminder)) {
            $this->reminders[] = $reminder;
            $reminder->setReminderList($this);
        }

        return $this;
    }

    public function removeReminder(Reminders $reminder): self
    {
        if ($this->reminders->removeElement($reminder)) {
            // set the owning side to null (unless already changed)
            if ($reminder->getReminderList() === $this) {
                $reminder->setReminderList(null);
            }
        }

        return $this;
    }
}
