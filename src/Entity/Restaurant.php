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

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="restaurants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToMany(targetEntity=FoodAllergens::class, mappedBy="restaurant")
     */
    private $foodAllergens;

    /**
     * @ORM\OneToMany(targetEntity=FoodImages::class, mappedBy="restaurant")
     */
    private $foodImages;

    /**
     * @ORM\ManyToMany(targetEntity=Food::class, mappedBy="restaurant")
     */
    private $food;

    /**
     * @ORM\OneToMany(targetEntity=Menu::class, mappedBy="restaurant")
     */
    private $menus;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Sale::class, mappedBy="restaurant")
     */
    private $sales;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="restaurant")
     */
    private $notifications;

    public function __construct()
    {
        $this->openingTimes = new ArrayCollection();
        $this->restaurantCategories = new ArrayCollection();
        $this->foodAllergens = new ArrayCollection();
        $this->foodImages = new ArrayCollection();
        $this->food = new ArrayCollection();
        $this->menus = new ArrayCollection();
        $this->sales = new ArrayCollection();
        $this->notifications = new ArrayCollection();
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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

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
            $foodAllergen->setRestaurant($this);
        }

        return $this;
    }

    public function removeFoodAllergen(FoodAllergens $foodAllergen): self
    {
        if ($this->foodAllergens->removeElement($foodAllergen)) {
            $foodAllergen->setRestaurant(null);
        }

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
            $foodImage->setRestaurant($this);
        }

        return $this;
    }

    public function removeFoodImage(FoodImages $foodImage): self
    {
        if ($this->foodImages->removeElement($foodImage)) {
            $foodImage->setRestaurant(null);
        }

        return $this;
    }

    /**
     * @return Collection|Food[]
     */
    public function getFood(): Collection
    {
        return $this->food;
    }

    public function addFood(Food $food): self
    {
        if (!$this->food->contains($food)) {
            $this->food[] = $food;
            $food->addRestaurant($this);
        }

        return $this;
    }

    public function removeFood(Food $food): self
    {
        if ($this->food->removeElement($food)) {
            $food->removeRestaurant($this);
        }

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
            $menu->addRestaurant($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            $menu->removeRestaurant($this);
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return Collection|Sale[]
     */
    public function getSales(): Collection
    {
        return $this->sales;
    }

    public function addSale(Sale $sale): self
    {
        if (!$this->sales->contains($sale)) {
            $this->sales[] = $sale;
            $sale->setRestaurant($this);
        }

        return $this;
    }

    public function removeSale(Sale $sale): self
    {
        if ($this->sales->removeElement($sale)) {
            // set the owning side to null (unless already changed)
            if ($sale->getRestaurant() === $this) {
                $sale->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setRestaurant($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getRestaurant() === $this) {
                $notification->setRestaurant(null);
            }
        }

        return $this;
    }
}
