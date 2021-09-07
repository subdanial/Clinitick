<?php

namespace App\Entity;

use App\Repository\TransactionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionsRepository::class)
 *
 * Related Entities {
 * Treatment
 * Customer
 * Payables
 * Incomes
 * }
 */
class Transactions
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
    private $related_entity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $related_entity_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_date;

    /**
     * @ORM\OneToMany(targetEntity=TransactionDetails::class, mappedBy="transaction")
     */
    private $transactionDetails;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $uid;

    public function __construct()
    {
        $this->transactionDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|TransactionDetails[]
     */
    public function getTransactionDetails(): Collection
    {
        return $this->transactionDetails;
    }

    public function addTransactionDetail(TransactionDetails $transactionDetail): self
    {
        if (!$this->transactionDetails->contains($transactionDetail)) {
            $this->transactionDetails[] = $transactionDetail;
            $transactionDetail->setTransaction($this);
        }

        return $this;
    }

    public function removeTransactionDetail(TransactionDetails $transactionDetail): self
    {
        if ($this->transactionDetails->removeElement($transactionDetail)) {
            // set the owning side to null (unless already changed)
            if ($transactionDetail->getTransaction() === $this) {
                $transactionDetail->setTransaction(null);
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
}
