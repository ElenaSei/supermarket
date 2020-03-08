<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BillRepository")
 */
class Bill
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalPrice;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BillItem", mappedBy="bill", orphanRemoval=true)
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->totalPrice = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->datetime = $date;

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(): self
    {
        foreach ($this->getItems() as $item) {
            $promotion = $item->getProduct()->getActivePromotion();

            if ($promotion){
                $remainder = $item->getQuantity() % $promotion->getQuantity();
                $quotient = ($item->getQuantity() - $remainder) / $promotion->getQuantity();

                $this->totalPrice += $remainder * $item->getProduct()->getPrice();
                $this->totalPrice += $quotient * $promotion->getPrice();
            } else {
                $this->totalPrice += $item->getQuantity() * $item->getProduct()->getPrice();
            }
        }

        return $this;
    }

    /**
     * @return Collection|BillItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(BillItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setBill($this);
        }

        return $this;
    }

    public function removeItem(BillItem $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getBill() === $this) {
                $item->setBill(null);
            }
        }

        return $this;
    }
}
