<?php

namespace App\Entity;

use App\Repository\TransactionDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionDetailsRepository::class)
 */
class TransactionDetails
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Transactions::class, inversedBy="transactionDetails")
     * @ORM\JoinColumn(nullable=false)
     */
    private $transaction;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $related_entity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $related_entity_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransaction(): ?Transactions
    {
        return $this->transaction;
    }

    public function setTransaction(?Transactions $transaction): self
    {
        $this->transaction = $transaction;

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

    public function getRelatedEntity(): ?string
    {
        return $this->related_entity;
    }

    public function setRelatedEntity(string $related_entity): self
    {
        $this->related_entity = $related_entity;

        return $this;
    }

    public function getRelatedEntityId(): ?int
    {
        return $this->related_entity_id;
    }

    public function setRelatedEntityId(?int $related_entity_id): self
    {
        $this->related_entity_id = $related_entity_id;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
