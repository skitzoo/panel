<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardAllLineRepository")
 */
class CardAllLine
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Card", inversedBy="cardAllLines")
     */
    private $idCard;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"detail"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AllIngredient", inversedBy="cardAllLines")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"detail"})
     */
    private $Ingredient;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"detail"})
     */
    private $active;

    public function __construct()
    {
        $this->active = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCard(): ?Card
    {
        return $this->idCard;
    }

    public function setIdCard(?Card $idCard): self
    {
        $this->idCard = $idCard;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getIngredient(): ?AllIngredient
    {
        return $this->Ingredient;
    }

    public function setIngredient(?AllIngredient $Ingredient): self
    {
        $this->Ingredient = $Ingredient;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
