<?php

namespace App\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AllIngredientInProductRepository")
 */
class AllIngredientInProduct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="allIngredientInProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AllIngredient", inversedBy="allIngredientInProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Ingredient;

    public function getId(): ?int
    {
        return $this->id;
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
