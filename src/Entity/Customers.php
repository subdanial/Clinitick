<?php

namespace App\Entity;

use App\Repository\CustomersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomersRepository::class)
 */
class Customers
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
    private $reason;

    /**
     * @ORM\Column(type="boolean")
     */
    private $diabet;

    /**
     * @ORM\Column(type="boolean")
     */
    private $asm;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hepatit;

    /**
     * @ORM\Column(type="boolean")
     */
    private $aids;

    /**
     * @ORM\Column(type="boolean")
     */
    private $kolie;

    /**
     * @ORM\Column(type="boolean")
     */
    private $saar;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etiad;

    /**
     * @ORM\Column(type="boolean")
     */
    private $bardari;

    /**
     * @ORM\Column(type="boolean")
     */
    private $romatism;

    /**
     * @ORM\Column(type="boolean")
     */
    private $shimi_darmani;

    /**
     * @ORM\Column(type="boolean")
     */
    private $eneeghad;

    /**
     * @ORM\Column(type="boolean")
     */
    private $saratan;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ghalb;

    /**
     * @ORM\Column(type="boolean")
     */
    private $feshar_khun;

    /**
     * @ORM\Column(type="boolean")
     */
    private $alergy;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=CustomerMedias::class, mappedBy="customer")
     */
    private $customerMedias;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_date;

    /**
     * @ORM\OneToMany(targetEntity=Appointments::class, mappedBy="customer")
     */
    private $appointments;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $uid;

    /**
     * @ORM\OneToMany(targetEntity=DoctorCustomers::class, mappedBy="customer")
     */
    private $doctorCustomers;

    public function __construct()
    {
        $this->customerMedias = new ArrayCollection();
        $this->appointments = new ArrayCollection();
        $this->doctorCustomers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFullMobile(): ?string
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

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getDiabet(): ?bool
    {
        return $this->diabet;
    }

    public function setDiabet(bool $diabet): self
    {
        $this->diabet = $diabet;

        return $this;
    }

    public function getAsm(): ?bool
    {
        return $this->asm;
    }

    public function setAsm(bool $asm): self
    {
        $this->asm = $asm;

        return $this;
    }

    public function getHepatit(): ?bool
    {
        return $this->hepatit;
    }

    public function setHepatit(bool $hepatit): self
    {
        $this->hepatit = $hepatit;

        return $this;
    }

    public function getAids(): ?bool
    {
        return $this->aids;
    }

    public function setAids(bool $aids): self
    {
        $this->aids = $aids;

        return $this;
    }

    public function getKolie(): ?bool
    {
        return $this->kolie;
    }

    public function setKolie(bool $kolie): self
    {
        $this->kolie = $kolie;

        return $this;
    }

    public function getSaar(): ?bool
    {
        return $this->saar;
    }

    public function setSaar(bool $saar): self
    {
        $this->saar = $saar;

        return $this;
    }

    public function getEtiad(): ?bool
    {
        return $this->etiad;
    }

    public function setEtiad(bool $etiad): self
    {
        $this->etiad = $etiad;

        return $this;
    }

    public function getBardari(): ?bool
    {
        return $this->bardari;
    }

    public function setBardari(bool $bardari): self
    {
        $this->bardari = $bardari;

        return $this;
    }

    public function getRomatism(): ?bool
    {
        return $this->romatism;
    }

    public function setRomatism(bool $romatism): self
    {
        $this->romatism = $romatism;

        return $this;
    }

    public function getShimiDarmani(): ?bool
    {
        return $this->shimi_darmani;
    }

    public function setShimiDarmani(bool $shimi_darmani): self
    {
        $this->shimi_darmani = $shimi_darmani;

        return $this;
    }

    public function getEneeghad(): ?bool
    {
        return $this->eneeghad;
    }

    public function setEneeghad(bool $eneeghad): self
    {
        $this->eneeghad = $eneeghad;

        return $this;
    }

    public function getSaratan(): ?bool
    {
        return $this->saratan;
    }

    public function setSaratan(bool $saratan): self
    {
        $this->saratan = $saratan;

        return $this;
    }

    public function getGhalb(): ?bool
    {
        return $this->ghalb;
    }

    public function setGhalb(bool $ghalb): self
    {
        $this->ghalb = $ghalb;

        return $this;
    }

    public function getFesharKhun(): ?bool
    {
        return $this->feshar_khun;
    }

    public function setFesharKhun(bool $feshar_khun): self
    {
        $this->feshar_khun = $feshar_khun;

        return $this;
    }

    public function getAlergy(): ?bool
    {
        return $this->alergy;
    }

    public function setAlergy(bool $alergy): self
    {
        $this->alergy = $alergy;

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

    /**
     * @return Collection|CustomerMedias[]
     */
    public function getCustomerMedias(): Collection
    {
        return $this->customerMedias;
    }

    public function addCustomerMedia(CustomerMedias $customerMedia): self
    {
        if (!$this->customerMedias->contains($customerMedia)) {
            $this->customerMedias[] = $customerMedia;
            $customerMedia->setCustomer($this);
        }

        return $this;
    }

    public function removeCustomerMedia(CustomerMedias $customerMedia): self
    {
        if ($this->customerMedias->removeElement($customerMedia)) {
            // set the owning side to null (unless already changed)
            if ($customerMedia->getCustomer() === $this) {
                $customerMedia->setCustomer(null);
            }
        }

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
            $appointment->setCustomer($this);
        }

        return $this;
    }

    public function removeAppointment(Appointments $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getCustomer() === $this) {
                $appointment->setCustomer(null);
            }
        }

        return $this;
    }

    public function getFullname() {
        return $this->getFirstName() . ' ' . $this->getLastName();
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
            $doctorCustomer->setCustomer($this);
        }

        return $this;
    }

    public function removeDoctorCustomer(DoctorCustomers $doctorCustomer): self
    {
        if ($this->doctorCustomers->removeElement($doctorCustomer)) {
            // set the owning side to null (unless already changed)
            if ($doctorCustomer->getCustomer() === $this) {
                $doctorCustomer->setCustomer(null);
            }
        }

        return $this;
    }
}
