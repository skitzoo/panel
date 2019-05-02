<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardRepository")
 */
class Card
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"detail"})
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Orders", inversedBy="cards")
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="cards")
     * @Groups({"detail"})
     */
    private $product;

    /**
     * @ORM\Column(type="boolean")
     */
    private $inProgress;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CardAllLine", mappedBy="idCard")
     * @Groups({"detail"})
     */
    private $cardAllLines;

    public function __construct()
    {
        $this->idCustom = false;
        $this->inProgress = false;
        $this->cardAllLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOrders(): ?Orders
    {
        return $this->orders;
    }

    public function setOrders(?Orders $orders): self
    {
        $this->orders = $orders;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getInProgress(): ?bool
    {
        return $this->inProgress;
    }

    public function setInProgress(bool $inProgress): self
    {
        $this->inProgress = $inProgress;

        return $this;
    }

    public function getTotalPrice()
    {
        return $this->getProduct()->getPrice();
    }

    /**
     * @return Collection|CardAllLine[]
     */
    public function getCardAllLines(): Collection
    {
        return $this->cardAllLines;
    }

    public function addCardAllLine(CardAllLine $cardAllLine): self
    {
        if (!$this->cardAllLines->contains($cardAllLine)) {
            $this->cardAllLines[] = $cardAllLine;
            $cardAllLine->setIdCard($this);
        }

        return $this;
    }

    public function removeCardAllLine(CardAllLine $cardAllLine): self
    {
        if ($this->cardAllLines->contains($cardAllLine)) {
            $this->cardAllLines->removeElement($cardAllLine);
            // set the owning side to null (unless already changed)
            if ($cardAllLine->getIdCard() === $this) {
                $cardAllLine->setIdCard(null);
            }
        }

        return $this;
    }
}
