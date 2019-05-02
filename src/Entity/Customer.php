<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 * @UniqueEntity(fields={"email"}, message="Adresse mail déjà utilisée")
 * @UniqueEntity(fields={"mobile"}, message="Mobile déjà utilisé")
 */
class Customer implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @Assert\Length(max=250)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="integer")
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $mobile;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BookingInfos", mappedBy="customer")
     */
    private $bookingInfos;

    /**
     * @ORM\Column(type="date")
     */
    private $birthday;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $passwordRequestAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    public function __construct()
    {
        $this->isActive = true;
        $this->bookingInfos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(int $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function eraseCredentials()
    {

    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function getSalt()
    {

    }

    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @return Collection|BookingInfos[]
     */
    public function getBookingInfos(): Collection
    {
        return $this->bookingInfos;
    }

    public function addBookingInfo(BookingInfos $bookingInfo): self
    {
        if (!$this->bookingInfos->contains($bookingInfo)) {
            $this->bookingInfos[] = $bookingInfo;
            $bookingInfo->setCustomer($this);
        }

        return $this;
    }

    public function removeBookingInfo(BookingInfos $bookingInfo): self
    {
        if ($this->bookingInfos->contains($bookingInfo)) {
            $this->bookingInfos->removeElement($bookingInfo);
            // set the owning side to null (unless already changed)
            if ($bookingInfo->getCustomer() === $this) {
                $bookingInfo->setCustomer(null);
            }
        }

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getPasswordRequestAt(): ?\DateTimeInterface
    {
        return $this->passwordRequestAt;
    }

    public function setPasswordRequestAt(\DateTimeInterface $passwordRequestAt): self
    {
        $this->passwordRequestAt = $passwordRequestAt;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function __toString()
    {
        return $this->getLastname() . " " . $this->getFirstname();
    }
}
