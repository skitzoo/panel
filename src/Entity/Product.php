<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    const TYPE_GENERAL              = 1;  # Ajout direct au panier
    //const TYPE_COMPOSED             = 2;  # Composition du produit
    const TYPE_MENU                 = 4;  # Menu composé (le prix ne change pas en fonction des produits choisis)
    const TYPE_MEAT_ALONE           = 5;
    //const TYPE_COMPOSED_WITH_REMOVE = 6;  # le prix ne change pas même si le client retire des produits
    const TYPE_ONE_MEAT             = 7;  # utilisé pour le tacos, le prix est fixe et ne change pas
    const TYPE_TWO_MEAT             = 8;  # utilisé pour le tacos, le prix est fixe et ne change pas
    const TYPE_THREE_MEAT           = 9;  # utilisé pour le tacos, le prix est fixe et ne change pas
    const TYPE_FOUR_MEAT            = 10; # utilisé pour le tacos, le prix est fixe et ne change pas
    const TYPE_HAMBURGER            = 11; # Choix cuisson(uniquement pour les viandes), sauce
    const TYPE_SPECIALITY           = 12; # Choix viande, condimentsdu pain, cuisson (uniquement pour les viandes), sauce
    const TYPE_ASSIETTE             = 13; # Choix viande, sauce, condiments, suppléments + ajout/retrait
    const TYPE_PANINI               = 14; # Choix cuisson (uniquement pour les viandes), sauce
    const TYPE_SANDWICH             = 15;
    const TYPE_AMERICAIN            = 16;
    const TYPE_COMPOSED             = 17;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"detail"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var string $image2
     * @Assert\File( maxSize = "10M", mimeTypes={ "image/jpeg", "image/png" }, mimeTypesMessage="Merci d'uploader une image valide")
     */
    private $image2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @ORM\OrderBy({"ordre" = "DESC", "id" = "DESC"})
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="products")
     * @Groups({"detail"})
     * @ORM\OrderBy({"ordre" = "ASC"})
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Card", mappedBy="product")
     */
    private $cards;

    /**
     * @ORM\Column(type="boolean")
     */
    private $meat;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"detail"})
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AllIngredientDefault", mappedBy="Product")
     */
    private $AllIngredientDefaults;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AllIngredientInProduct", mappedBy="product")
     */
    private $allIngredientInProducts;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
        $this->available = true;
        $this->AllIngredientDefaults = new ArrayCollection();
        $this->allIngredientInProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImage2(): ?string
    {
        return $this->image2;
    }

    public function setImage2(string $image2): self
    {
        $this->image = $image2;

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

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection|Card[]
     */
    public function getOrderQuantityProducts(): Collection
    {
        return $this->cards;
    }

    public function addOrderQuantityProduct(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setProduct($this);
        }

        return $this;
    }

    public function removeOrderQuantityProduct(Card $card): self
    {
        if ($this->cards->contains($card)) {
            $this->cards->removeElement($card);
            // set the owning side to null (unless already changed)
            if ($card->getProduct() === $this) {
                $card->setProduct(null);
            }
        }

        return $this;
    }

    public function getMeat(): ?bool
    {
        return $this->meat;
    }

    public function setMeat(bool $meat): self
    {
        $this->meat = $meat;

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

    public function __toString()
    {
        return $this->categorie->getName() . ' - '.$this->name;
    }

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setProduct($this);
        }

        return $this;
    }

    public function removeCardAll(): self
    {
        foreach($this->cards as $card)
        {
            $card->setProduct(null);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->contains($card)) {
            $this->cards->removeElement($card);
            // set the owning side to null (unless already changed)
            if ($card->getProduct() === $this) {
                $card->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AllIngredientDefault[]
     */
    public function getAllIngredientDefaults(): Collection
    {
        return $this->AllIngredientDefaults;
    }

    public function addAllIngredientDefault(AllIngredientDefault $allIngredientDefault): self
    {
        if (!$this->AllIngredientDefaults->contains($allIngredientDefault)) {
            $this->AllIngredientDefaults[] = $allIngredientDefault;
            $allIngredientDefault->setProduct($this);
        }

        return $this;
    }

    public function removeAllIngredientDefault(AllIngredientDefault $allIngredientDefault): self
    {
        if ($this->AllIngredientDefaults->contains($allIngredientDefault)) {
            $this->AllIngredientDefaults->removeElement($allIngredientDefault);
            // set the owning side to null (unless already changed)
            if ($allIngredientDefault->getProduct() === $this) {
                $allIngredientDefault->setProduct(null);
            }
        }

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
            $allIngredientInProduct->setProduct($this);
        }

        return $this;
    }

    public function removeAllIngredientInProduct(AllIngredientInProduct $allIngredientInProduct): self
    {
        if ($this->allIngredientInProducts->contains($allIngredientInProduct)) {
            $this->allIngredientInProducts->removeElement($allIngredientInProduct);
            // set the owning side to null (unless already changed)
            if ($allIngredientInProduct->getProduct() === $this) {
                $allIngredientInProduct->setProduct(null);
            }
        }

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
