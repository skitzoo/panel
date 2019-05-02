<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AllIngredientDefaultRepository")
 */
class AllIngredientDefault
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="AllIngredientDefaults")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AllIngredient", inversedBy="allIngredientDefaults")
     */
    private $Ingredient;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->Product;
    }

    public function setProduct(?Product $Product): self
    {
        $this->Product = $Product;

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
}
