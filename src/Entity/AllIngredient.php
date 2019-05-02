<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AllIngredientRepository")
 */
class AllIngredient
{
    const TYPE_REMOVE = 0;
    const TYPE_MEAT = 1;
    const TYPE_BREAD = 2;
    const TYPE_SAUCE = 3;
    const TYPE_CUISSON = 4;
    const TYPE_SUPPLEMENT = 5;
    const TYPE_CONDIMENT = 6;
    const TYPE_PLAT = 9;
    const TYPE_UNCHANGEABLE = 12;
    const TYPE_CHEESE = 14;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"detail"})
     */
    private $Name;

    /**
     * @ORM\Column(type="integer")
     */
    private $Type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AllIngredientDefault", mappedBy="Ingredient")
     */
    private $allIngredientDefaults;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CardAllLine", mappedBy="Ingredient")
     */
    private $cardAllLines;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $picture;

    /**
     * @var string $picture2
     * @Assert\File( maxSize = "10M", mimeTypes={ "image/jpeg", "image/png" }, mimeTypesMessage="Merci d'uploader une image valide")
     */
    private $picture2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ordre;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $available;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AllIngredientInProduct", mappedBy="Ingredient")
     */
    private $allIngredientInProducts;

    public function __construct()
    {
        $this->allIngredientDefaults = new ArrayCollection();
        $this->cardAllLines = new ArrayCollection();
        $this->allIngredientInProducts = new ArrayCollection();
        $this->available = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->Type;
    }

    public function setType(int $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    /**
     * @return Collection|AllIngredientDefault[]
     */
    public function getTacosIngredientDefaults(): Collection
    {
        return $this->allIngredientDefaults;
    }

    public function addTacosIngredientDefault(AllIngredientDefault $tacosIngredientDefault): self
    {
        if (!$this->allIngredientDefaults->contains($tacosIngredientDefault)) {
            $this->allIngredientDefaults[] = $tacosIngredientDefault;
            $tacosIngredientDefault->setIngredient($this);
        }

        return $this;
    }

    public function removeTacosIngredientDefault(AllIngredientDefault $tacosIngredientDefault): self
    {
        if ($this->allIngredientDefaults->contains($tacosIngredientDefault)) {
            $this->allIngredientDefaults->removeElement($tacosIngredientDefault);
            // set the owning side to null (unless already changed)
            if ($tacosIngredientDefault->getIngredient() === $this) {
                $tacosIngredientDefault->setIngredient(null);
            }
        }

        return $this;
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
            $cardAllLine->setIngredient($this);
        }

        return $this;
    }

    public function removeCardAllLine(CardAllLine $cardAllLine): self
    {
        if ($this->cardAllLines->contains($cardAllLine)) {
            $this->cardAllLines->removeElement($cardAllLine);
            // set the owning side to null (unless already changed)
            if ($cardAllLine->getIngredient() === $this) {
                $cardAllLine->setIngredient(null);
            }
        }

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPicture2(): ?string
    {
        return $this->picture2;
    }

    public function setPicture2(string $picture2): self
    {
        $this->picture = $picture2;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    /**
     * @return Collection|AllIngredientInProduct[]
     */
    public function getAllIngredientInProducts(): Collection
    {
        return $this->allIngredientInProducts;
    }

    public function addAllIngredientInProduct(AllIngredientInProduct $allIngredientInProduct): self
    {
        if (!$this->allIngredientInProducts->contains($allIngredientInProduct)) {
            $this->allIngredientInProducts[] = $allIngredientInProduct;
            $allIngredientInProduct->setIngredient($this);
        }

        return $this;
    }

    public function removeAllIngredientInProduct(AllIngredientInProduct $allIngredientInProduct): self
    {
        if ($this->allIngredientInProducts->contains($allIngredientInProduct)) {
            $this->allIngredientInProducts->removeElement($allIngredientInProduct);
            // set the owning side to null (unless already changed)
            if ($allIngredientInProduct->getIngredient() === $this) {
                $allIngredientInProduct->setIngredient(null);
            }
        }

        return $this;
    }

    public function getTypeName()
    {
        if ($this->getType() == self::TYPE_REMOVE)
            return '(Ingrédient de base) '. $this->Name;
        elseif ($this->getType() == self::TYPE_MEAT)
            return '(Viande) '. $this->Name;
        elseif ($this->getType() == self::TYPE_BREAD)
            return '(Pain) '. $this->Name;
        elseif ($this->getType() == self::TYPE_SAUCE)
            return '(Sauce) '. $this->Name;
        elseif ($this->getType() == self::TYPE_CUISSON)
            return '(Cuisson) '. $this->Name;
        elseif ($this->getType() == self::TYPE_SUPPLEMENT)
            return '(Supplément) '. $this->Name;
        elseif ($this->getType() == self::TYPE_CONDIMENT)
            return '(Condiment) '. $this->Name;
        elseif ($this->getType() == self::TYPE_PLAT)
            return '(Plat) '. $this->Name;
        elseif ($this->getType() == self::TYPE_UNCHANGEABLE)
            return '(Non modifiable) '. $this->Name;
        elseif ($this->getType() == self::TYPE_CHEESE)
            return '(Fromage) '. $this->Name;

        return 'inconnu';
    }

    public function __toString()
    {
        if ($this->getType() == self::TYPE_REMOVE)
            return '(Ingrédient de base) '. $this->Name;
        elseif ($this->getType() == self::TYPE_MEAT)
            return '(Viande) '. $this->Name;
        elseif ($this->getType() == self::TYPE_BREAD)
            return '(Pain) '. $this->Name;
        elseif ($this->getType() == self::TYPE_SAUCE)
            return '(Sauce) '. $this->Name;
        elseif ($this->getType() == self::TYPE_CUISSON)
            return '(Cuisson) '. $this->Name;
        elseif ($this->getType() == self::TYPE_SUPPLEMENT)
            return '(Supplément) '. $this->Name;
        elseif ($this->getType() == self::TYPE_CONDIMENT)
            return '(Condiment) '. $this->Name;
        elseif ($this->getType() == self::TYPE_PLAT)
            return '(Plat) '. $this->Name;
        elseif ($this->getType() == self::TYPE_UNCHANGEABLE)
            return '(Non modifiable) '. $this->Name;
        elseif ($this->getType() == self::TYPE_CHEESE)
            return '(Fromage) '. $this->Name;
    }
}
