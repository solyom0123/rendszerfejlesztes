<?php

namespace App\Entity;

use App\Repository\FoodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FoodRepository::class)
 */
class Food
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fromDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $toDate;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=FoodImages::class, mappedBy="food")
     */
    private $foodImages;

    /**
     * @ORM\ManyToMany(targetEntity=FoodAllergens::class, mappedBy="food")
     */
    private $foodAllergens;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Menu::class, mappedBy="foods")
     */
    private $menus;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class, inversedBy="food")
     */
    private $restaurant;

    /**
     * @ORM\ManyToMany(targetEntity=Sale::class, mappedBy="foods")
     */
    private $yes;

    public function __construct()
    {
        $this->foodImages = new ArrayCollection();
        $this->foodAllergens = new ArrayCollection();
        $this->menus = new ArrayCollection();
        $this->sales = new ArrayCollection();
        $this->yes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromDate(): ?\DateTimeInterface
    {
        return $this->fromDate;
    }

    public function setFromDate(?\DateTimeInterface $fromDate): self
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    public function getToDate(): ?\DateTimeInterface
    {
        return $this->toDate;
    }

    public function setToDate(?\DateTimeInterface $toDate): self
    {
        $this->toDate = $toDate;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
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

    /**
     * @return Collection|FoodImages[]
     */
    public function getFoodImages(): Collection
    {
        return $this->foodImages;
    }

    public function addFoodImage(FoodImages $foodImage): self
    {
        if (!$this->foodImages->contains($foodImage)) {
            $this->foodImages[] = $foodImage;
            $foodImage->setFood($this);
        }

        return $this;
    }

    public function removeFoodImage(FoodImages $foodImage): self
    {
        if ($this->foodImages->removeElement($foodImage)) {
            // set the owning side to null (unless already changed)
            if ($foodImage->getFood() === $this) {
                $foodImage->setFood(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FoodAllergens[]
     */
    public function getFoodAllergens(): Collection
    {
        return $this->foodAllergens;
    }

    public function addFoodAllergen(FoodAllergens $foodAllergen): self
    {
        if (!$this->foodAllergens->contains($foodAllergen)) {
            $this->foodAllergens[] = $foodAllergen;
            $foodAllergen->addFood($this);
        }

        return $this;
    }

    public function removeFoodAllergen(FoodAllergens $foodAllergen): self
    {
        if ($this->foodAllergens->removeElement($foodAllergen)) {
            // set the owning side to null (unless already changed)
            if ($foodAllergen->getFood() === $this) {
                $foodAllergen->removeFood($this);
            }
        }

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
     * @return Collection|Menu[]
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->addFood($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            $menu->removeFood($this);
        }

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
    public function __toString()
    {
       return $this->getName();
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
            $ye->addFood($this);
        }

        return $this;
    }

    public function removeYe(Sale $ye): self
    {
        if ($this->yes->removeElement($ye)) {
            $ye->removeFood($this);
        }

        return $this;
    }

}
