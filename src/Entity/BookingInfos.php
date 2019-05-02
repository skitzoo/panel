<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingInfosRepository")
 */
class BookingInfos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $scheduleAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="bookingInfos")
     */
    private $customer;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Orders", inversedBy="bookingInfos", cascade={"persist", "remove"})
     */
    private $ord;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScheduleAt(): ?string
    {
        return $this->scheduleAt;
    }

    public function setScheduleAt(string $scheduleAt): self
    {
        $this->scheduleAt = $scheduleAt;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getOrd(): ?Orders
    {
        return $this->ord;
    }

    public function setOrd(?Orders $ord): self
    {
        $this->ord = $ord;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
