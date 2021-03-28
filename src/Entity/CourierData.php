<?php

namespace App\Entity;

use App\Repository\CourierDataRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourierDataRepository::class)
 */
class CourierData
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
    private $location;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mobileNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vehicleType;

    /**
     * @ORM\Column(type="time")
     */
    private $fromWorkingDateMonday;

    /**
     * @ORM\Column(type="time")
     */
    private $toWorkingDateMonday;

    /**
     * @ORM\Column(type="time")
     */
    private $fromWorkingDateTuesday;

    /**
     * @ORM\Column(type="time")
     */
    private $toWorkingDateTuesday;

    /**
     * @ORM\Column(type="time")
     */
    private $fromWorkingDateWednesday;

    /**
     * @ORM\Column(type="time")
     */
    private $toWorkingDateWednesday;

    /**
     * @ORM\Column(type="time")
     */
    private $fromWorkingDateThursday;

    /**
     * @ORM\Column(type="time")
     */
    private $toWorkingDateThursday;

    /**
     * @ORM\Column(type="time")
     */
    private $fromWorkingDateFriday;

    /**
     * @ORM\Column(type="time")
     */
    private $toWorkingDateFriday;

    /**
     * @ORM\Column(type="time")
     */
    private $fromWorkingDateSaturday;

    /**
     * @ORM\Column(type="time")
     */
    private $toWorkingDateSaturday;

    /**
     * @ORM\Column(type="time")
     */
    private $fromWorkingDateSunday;

    /**
     * @ORM\Column(type="time")
     */
    private $toWorkingDateSunday;

    /**
     * @ORM\OneToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getMobileNumber(): ?string
    {
        return $this->mobileNumber;
    }

    public function setMobileNumber(string $mobileNumber): self
    {
        $this->mobileNumber = $mobileNumber;

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

    public function getVehicleType(): ?string
    {
        return $this->vehicleType;
    }

    public function setVehicleType(string $vehicleType): self
    {
        $this->vehicleType = $vehicleType;

        return $this;
    }

    public function getFromWorkingDateMonday(): ?\DateTimeInterface
    {
        return $this->fromWorkingDateMonday;
    }

    public function setFromWorkingDateMonday(\DateTimeInterface $fromWorkingDateMonday): self
    {
        $this->fromWorkingDateMonday = $fromWorkingDateMonday;

        return $this;
    }

    public function getToWorkingDateMonday(): ?\DateTimeInterface
    {
        return $this->toWorkingDateMonday;
    }

    public function setToWorkingDateMonday(\DateTimeInterface $toWorkingDateMonday): self
    {
        $this->toWorkingDateMonday = $toWorkingDateMonday;

        return $this;
    }

    public function getFromWorkingDateTuesday(): ?\DateTimeInterface
    {
        return $this->fromWorkingDateTuesday;
    }

    public function setFromWorkingDateTuesday(\DateTimeInterface $fromWorkingDateTuesday): self
    {
        $this->fromWorkingDateTuesday = $fromWorkingDateTuesday;

        return $this;
    }

    public function getToWorkingDateTuesday(): ?\DateTimeInterface
    {
        return $this->toWorkingDateTuesday;
    }

    public function setToWorkingDateTuesday(\DateTimeInterface $toWorkingDateTuesday): self
    {
        $this->toWorkingDateTuesday = $toWorkingDateTuesday;

        return $this;
    }

    public function getFromWorkingDateWednesday(): ?\DateTimeInterface
    {
        return $this->fromWorkingDateWednesday;
    }

    public function setFromWorkingDateWednesday(\DateTimeInterface $fromWorkingDateWednesday): self
    {
        $this->fromWorkingDateWednesday = $fromWorkingDateWednesday;

        return $this;
    }

    public function getToWorkingDateWednesday(): ?\DateTimeInterface
    {
        return $this->toWorkingDateWednesday;
    }

    public function setToWorkingDateWednesday(\DateTimeInterface $toWorkingDateWednesday): self
    {
        $this->toWorkingDateWednesday = $toWorkingDateWednesday;

        return $this;
    }

    public function getFromWorkingDateThursday(): ?\DateTimeInterface
    {
        return $this->fromWorkingDateThursday;
    }

    public function setFromWorkingDateThursday(\DateTimeInterface $fromWorkingDateThursday): self
    {
        $this->fromWorkingDateThursday = $fromWorkingDateThursday;

        return $this;
    }

    public function getToWorkingDateThursday(): ?\DateTimeInterface
    {
        return $this->toWorkingDateThursday;
    }

    public function setToWorkingDateThursday(\DateTimeInterface $toWorkingDateThursday): self
    {
        $this->toWorkingDateThursday = $toWorkingDateThursday;

        return $this;
    }

    public function getFromWorkingDateFriday(): ?\DateTimeInterface
    {
        return $this->fromWorkingDateFriday;
    }

    public function setFromWorkingDateFriday(\DateTimeInterface $fromWorkingDateFriday): self
    {
        $this->fromWorkingDateFriday = $fromWorkingDateFriday;

        return $this;
    }

    public function getToWorkingDateFriday(): ?\DateTimeInterface
    {
        return $this->toWorkingDateFriday;
    }

    public function setToWorkingDateFriday(\DateTimeInterface $toWorkingDateFriday): self
    {
        $this->toWorkingDateFriday = $toWorkingDateFriday;

        return $this;
    }

    public function getFromWorkingDateSaturday(): ?\DateTimeInterface
    {
        return $this->fromWorkingDateSaturday;
    }

    public function setFromWorkingDateSaturday(\DateTimeInterface $fromWorkingDateSaturday): self
    {
        $this->fromWorkingDateSaturday = $fromWorkingDateSaturday;

        return $this;
    }

    public function getToWorkingDateSaturday(): ?\DateTimeInterface
    {
        return $this->toWorkingDateSaturday;
    }

    public function setToWorkingDateSaturday(\DateTimeInterface $toWorkingDateSaturday): self
    {
        $this->toWorkingDateSaturday = $toWorkingDateSaturday;

        return $this;
    }

    public function getFromWorkingDateSunday(): ?\DateTimeInterface
    {
        return $this->fromWorkingDateSunday;
    }

    public function setFromWorkingDateSunday(\DateTimeInterface $fromWorkingDateSunday): self
    {
        $this->fromWorkingDateSunday = $fromWorkingDateSunday;

        return $this;
    }

    public function getToWorkingDateSunday(): ?\DateTimeInterface
    {
        return $this->toWorkingDateSunday;
    }

    public function setToWorkingDateSunday(\DateTimeInterface $toWorkingDateSunday): self
    {
        $this->toWorkingDateSunday = $toWorkingDateSunday;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
