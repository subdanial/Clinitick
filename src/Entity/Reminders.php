<?php

namespace App\Entity;

use App\Repository\RemindersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RemindersRepository::class)
 */
class Reminders
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_important;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $due_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_done;

    /**
     * @ORM\ManyToOne(targetEntity=ReminderLists::class, inversedBy="reminders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reminder_list;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $uid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsImportant(): ?bool
    {
        return $this->is_important;
    }

    public function setIsImportant(bool $is_important): self
    {
        $this->is_important = $is_important;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDueDate(?\DateTimeInterface $due_date): self
    {
        $this->due_date = $due_date;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsDone(): ?bool
    {
        return $this->is_done;
    }

    public function setIsDone(bool $is_done): self
    {
        $this->is_done = $is_done;

        return $this;
    }

    public function getReminderList(): ?ReminderLists
    {
        return $this->reminder_list;
    }

    public function setReminderList(?ReminderLists $reminder_list): self
    {
        $this->reminder_list = $reminder_list;

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
}
