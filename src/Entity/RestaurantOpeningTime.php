<?php

namespace App\Entity;

use App\Repository\RestaurantOpeningTimeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RestaurantOpeningTimeRepository::class)
 */
class RestaurantOpeningTime
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $day;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $openTime;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $closeTime;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class, inversedBy="openingTimes")
     */
    private $restaurant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(?string $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getOpenTime(): ?\DateTimeInterface
    {
        return $this->openTime;
    }

    public function setOpenTime(?\DateTimeInterface $openTime): self
    {
        $this->openTime = $openTime;

        return $this;
    }

    public function getCloseTime(): ?\DateTimeInterface
    {
        return $this->closeTime;
    }

    public function setCloseTime(?\DateTimeInterface $closeTime): self
    {
        $this->closeTime = $closeTime;

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }
}
