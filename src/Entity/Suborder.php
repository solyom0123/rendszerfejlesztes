<?php

namespace App\Entity;

use App\Repository\SuborderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SuborderRepository::class)
 */
class Suborder
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="suborders",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $parentOrder;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $courier;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $restaurant;

    /**
     * @ORM\ManyToMany(targetEntity=Food::class)
     */
    private $foods;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalPrice;

    public function __construct()
    {
        $this->foods = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParentOrder(): ?Order
    {
        return $this->parentOrder;
    }

    public function setParentOrder(?Order $parentOrder): self
    {
        $this->parentOrder = $parentOrder;

        return $this;
    }

    public function getCourier(): ?User
    {
        return $this->courier;
    }

    public function setCourier(?User $courier): self
    {
        $this->courier = $courier;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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

    /**
     * @return Collection|Food[]
     */
    public function getFoods(): Collection
    {
        return $this->foods;
    }

    public function addFood(Food $food): self
    {
            $this->foods[] = $food;
        return $this;
    }

    public function removeFood(Food $food): self
    {
        $this->foods->removeElement($food);

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }
    public function __toString()
    {
        return "".$this->getId();
    }
}
