<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RestaurantRepository::class)
 */
class Restaurant
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
    private $address;

    /**
     * @ORM\OneToMany(targetEntity=RestaurantOpeningTime::class, mappedBy="restaurant")
     */
    private $openingTimes;

    /**
     * @ORM\OneToMany(targetEntity=RestaurantCategory::class, mappedBy="alma")
     */
    private $restaurantCategories;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $waitingTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    public function __construct()
    {
        $this->openingTimes = new ArrayCollection();
        $this->restaurantCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection|RestaurantOpeningTime[]
     */
    public function getOpeningTimes(): Collection
    {
        return $this->openingTimes;
    }

    public function addOpeningTime(RestaurantOpeningTime $openingTime): self
    {
        if (!$this->openingTimes->contains($openingTime)) {
            $this->openingTimes[] = $openingTime;
            $openingTime->setRestaurant($this);
        }

        return $this;
    }

    public function removeOpeningTime(RestaurantOpeningTime $openingTime): self
    {
        if ($this->openingTimes->removeElement($openingTime)) {
            // set the owning side to null (unless already changed)
            if ($openingTime->getRestaurant() === $this) {
                $openingTime->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RestaurantCategory[]
     */
    public function getRestaurantCategories(): Collection
    {
        return $this->restaurantCategories;
    }

    public function addRestaurantCategory(RestaurantCategory $restaurantCategory): self
    {
        if (!$this->restaurantCategories->contains($restaurantCategory)) {
            $this->restaurantCategories[] = $restaurantCategory;
            $restaurantCategory->setAlma($this);
        }

        return $this;
    }

    public function removeRestaurantCategory(RestaurantCategory $restaurantCategory): self
    {
        if ($this->restaurantCategories->removeElement($restaurantCategory)) {
            // set the owning side to null (unless already changed)
            if ($restaurantCategory->getAlma() === $this) {
                $restaurantCategory->setAlma(null);
            }
        }

        return $this;
    }

    public function getWaitingTime(): ?\DateTimeInterface
    {
        return $this->waitingTime;
    }

    public function setWaitingTime(?\DateTimeInterface $waitingTime): self
    {
        $this->waitingTime = $waitingTime;

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
}
