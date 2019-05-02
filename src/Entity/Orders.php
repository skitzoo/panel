<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Orders
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"detail"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"detail"})
     */
    private $borneID;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"detail"})
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Card", mappedBy="orders")
     * @Groups({"detail"})
     */
    private $cards;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"detail"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"detail"})
     */
    private $typevalue;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $finishAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     */
    private $startAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\BookingInfos", mappedBy="ord", cascade={"persist", "remove"})
     */
    private $bookingInfos;

    public function __construct($borneId)
    {
        $this->borneID = $borneId;
        $this->cards = new ArrayCollection();
        $this->status = "En cours de commande";
        $this->type = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBorneID(): ?int
    {
        return $this->borneID;
    }

    public function setBorneID(int $borneID): self
    {
        $this->borneID = $borneID;

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

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $cards): self
    {
        if (!$this->cards->contains($cards)) {
            $this->cards[] = $cards;
            $cards->setOrders($this);
        }

        return $this;
    }

    public function removeCard(Card $cards): self
    {
        if ($this->cards->contains($cards)) {
            $this->cards->removeElement($cards);
            // set the owning side to null (unless already changed)
            if ($cards->getOrders() === $this) {
                $cards->setOrders(null);
            }
        }

        return $this;
    }

    public function getOrderTotal(): float
    {
        $total = 0;
        foreach ($this->cards as $row)
        {
            if ($row->getProduct() == null)
                continue;

            foreach ($row->getCardAllLines() as $line)
            {
                if($line->getType() != AllIngredient::TYPE_REMOVE and $line->getType() != AllIngredient::TYPE_MEAT and $line->getType() != AllIngredient::TYPE_BREAD and $line->getType() != AllIngredient::TYPE_SAUCE and $line->getType() != AllIngredient::TYPE_CUISSON and $line->getType() != AllIngredient::TYPE_CONDIMENT and $line->getType() != AllIngredient::TYPE_UNCHANGEABLE)
                    $total += $line->getIngredient()->getPrice();
            }
            $total += $row->getQuantity() * $row->getProduct()->getPrice();
        }
        return $total;
    }

    public function getTotalItems(): int
    {
        $item = 0;
        foreach ($this->cards as $row)
        {
            $item += $row->getQuantity();
        }
        return $item;
    }

    public function getCheckOrderNotEmpty(): bool
    {
        $find = true;

        if (count($this->cards) == 0)
        {
            $find = false;
        }

        if ($find == true) {
            foreach ($this->cards as $row) {
                if ($row->getInProgress()) {
                    $find = false;
                }
            }
        }

        return $find;
    }

    public function getType(): ?bool
    {
        return $this->type;
    }

    public function setType(bool $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTypevalue(): ?string
    {
        return $this->typevalue;
    }

    public function setTypevalue(string $typevalue): self
    {
        $this->typevalue = $typevalue;

        return $this;
    }

    public function getFinishAt(): ?\DateTimeInterface
    {
        return $this->finishAt;
    }

    public function setFinishAt(?\DateTimeInterface $finishAt): self
    {
        $this->finishAt = $finishAt;

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getBookingInfos(): ?BookingInfos
    {
        return $this->bookingInfos;
    }

    public function setBookingInfos(?BookingInfos $bookingInfos): self
    {
        $this->bookingInfos = $bookingInfos;

        // set (or unset) the owning side of the relation if necessary
        $newOrd = $bookingInfos === null ? null : $this;
        if ($newOrd !== $bookingInfos->getOrd()) {
            $bookingInfos->setOrd($newOrd);
        }

        return $this;
    }
}
