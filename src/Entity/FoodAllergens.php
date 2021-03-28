<?php

namespace App\Entity;

use App\Repository\FoodAllergensRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use http\Cookie;

/**
 * @ORM\Entity(repositoryClass=FoodAllergensRepository::class)
 */
class FoodAllergens
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
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Food::class, inversedBy="foodAllergen")
     */
    private $food;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class, inversedBy="foodAllergens")
     */
    private $restaurant;

    public function __construct()
    {
        $this->food = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFood(): ?Collection
    {
        return $this->food;
    }

    public function addFood(?Food $food): self
    {
        if (!$this->food->contains($food)) {
            $this->food[] = $food;
        }

        return $this;
    }

    public function removeFood(Food $food): self
    {
        if ($this->food->removeElement($food)) {
                $food->removeFoodAllergen($this);
        }

        return $this;
    }

    public function getRestaurant():Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(Restaurant $restaurant): self
    {
           $this->restaurant = $restaurant;

        return $this;
    }
}
