<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=Suborder::class, mappedBy="parentOrder", orphanRemoval=true)
     */
    private $suborders;

    /**
     * @ORM\ManyToMany(targetEntity=Food::class)
     */
    private $foods;


    public function __construct()
    {
        $this->suborders = new ArrayCollection();
        $this->foods = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): self
    {
        $this->customer = $customer;

        return $this;
    }


    

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|Suborder[]
     */
    public function getSuborders(): Collection
    {
        return $this->suborders;
    }

    public function addSuborder(Suborder $suborder): self
    {
        if (!$this->suborders->contains($suborder)) {
            $this->suborders[] = $suborder;
            $suborder->setParentOrder($this);
        }

        return $this;
    }

    public function removeSuborder(Suborder $suborder): self
    {
        if ($this->suborders->removeElement($suborder)) {
            // set the owning side to null (unless already changed)
            if ($suborder->getParentOrder() === $this) {
                $suborder->setParentOrder(null);
            }
        }

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

}
