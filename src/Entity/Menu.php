<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 */
class Menu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=MenuCategory::class, inversedBy="menus")
     */
    private $menuCategory;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Food::class, inversedBy="menus")
     */
    private $foods;

    /**
     * @ORM\ManyToMany(targetEntity=Restaurant::class, inversedBy="menus")
     */
    private $restaurant;

    public function __construct()
    {
        $this->menuCategory = new ArrayCollection();
        $this->foods = new ArrayCollection();
        $this->restaurant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|MenuCategory[]
     */
    public function getMenuCategory(): Collection
    {
        return $this->menuCategory;
    }

    public function addMenuCategory(MenuCategory $menuCategory): self
    {
        if (!$this->menuCategory->contains($menuCategory)) {
            $this->menuCategory[] = $menuCategory;
        }

        return $this;
    }

    public function removeMenuCategory(MenuCategory $menuCategory): self
    {
        $this->menuCategory->removeElement($menuCategory);

        return $this;
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

    /**
     * @return Collection|Food[]
     */
    public function getFoods(): Collection
    {
        return $this->foods;
    }

    public function addFood(Food $food): self
    {
        if (!$this->foods->contains($food)) {
            $this->foods[] = $food;
        }

        return $this;
    }

    public function removeFood(Food $food): self
    {
        $this->foods->removeElement($food);

        return $this;
    }

    /**
     * @return Collection|Restaurant[]
     */
    public function getRestaurant(): Collection
    {
        return $this->restaurant;
    }

    public function addRestaurant(Restaurant $restaurant): self
    {
        if (!$this->restaurant->contains($restaurant)) {
            $this->restaurant[] = $restaurant;
        }

        return $this;
    }

    public function removeRestaurant(Restaurant $restaurant): self
    {
        $this->restaurant->removeElement($restaurant);

        return $this;
    }
}
