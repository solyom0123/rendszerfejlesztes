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
     * @ORM\ManyToOne(targetEntity=Restaurant::class, inversedBy="menus")
     */
    private $restaurant;

    /**
     * @ORM\ManyToMany(targetEntity=Sale::class, mappedBy="menus")
     */
    private $yes;

    public function __construct()
    {
        $this->menuCategory = new ArrayCollection();
        $this->foods = new ArrayCollection();
        $this->yes = new ArrayCollection();
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


    public function getRestaurant(): Restaurant
    {
        return $this->restaurant;
    }


    public function setRestaurant(Restaurant $restaurant): self
    {
        $this->restaurant=$restaurant;

        return $this;
    }


    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|Sale[]
     */
    public function getYes(): Collection
    {
        return $this->yes;
    }

    public function addYe(Sale $ye): self
    {
        if (!$this->yes->contains($ye)) {
            $this->yes[] = $ye;
            $ye->addMenu($this);
        }

        return $this;
    }

    public function removeYe(Sale $ye): self
    {
        if ($this->yes->removeElement($ye)) {
            $ye->removeMenu($this);
        }

        return $this;
    }
}
