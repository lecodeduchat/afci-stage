<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Repository\UsersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Il y a déjà un compte avec cette adresse email.')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    use CreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Veuillez renseigner votre adresse email.')]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(options: ['default' => 'ROLE_USER'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Assert\NotBlank(message: 'Veuillez renseigner votre nom.')]
    #[ORM\Column(length: 100)]
    private ?string $firstname = null;

    #[Assert\NotBlank(message: 'Veuillez renseigner votre prénom.')]
    #[ORM\Column(length: 100)]
    private ?string $lastname = null;

    #[Assert\NotBlank(message: 'Veuillez renseigner votre date de naissance.')]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthday = null;

    #[Assert\NotBlank(message: 'Veuillez renseigner votre adresse.')]
    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[Assert\NotBlank(message: 'Veuillez renseigner votre code postal.')]
    #[Assert\Regex(pattern: '/^[0-9]{5}$/', message: 'Le code postal doit comporter 5 chiffres.')]
    #[Assert\Length(min: 5, max: 5, exactMessage: 'Le code postal doit comporter 5 chiffres.')]
    #[ORM\Column(length: 5)]
    private ?string $zipcode = null;

    #[Assert\NotBlank(message: 'Veuillez renseigner votre ville.')]
    #[ORM\Column(length: 150)]
    private ?string $city = null;

    #[Assert\Regex(pattern: '/^[0-9]{10}$/', message: 'Le numéro de téléphone doit comporter 10 chiffres.')]
    #[Assert\NotBlank(message: 'Veuillez renseigner votre numéro de téléphone.')]
    #[ORM\Column(length: 10)]
    private ?string $phone = null;

    #[ORM\Column]
    private ?bool $is_blocked = false;

    #[ORM\Column(type: 'boolean')]
    private $is_verified = false;

    #[ORM\Column (type: 'string', length: 100, nullable:true)]
    private $resetToken;
    

    public function __construct()
    {
        // Pour le champ created_at, on ajoute la date du jour
        $this->created_at = new \DateTimeImmutable();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function isIsBlocked(): ?bool
    {
        return $this->is_blocked;
    }

    public function setIsBlocked(bool $is_blocked): self
    {
        $this->is_blocked = $is_blocked;

        return $this;
    }


    public function __toString()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getIsverified(): ?bool
    {
        return $this->is_verified;
    }

    public function setIsverified(bool $is_verified): self
    {
        $this->is_verified = $is_verified;

        return $this;
    }


    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self 
    {
       $this->resetToken = $resetToken;
       return $this;
    }



}
