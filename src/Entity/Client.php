<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email.')]
class Client implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = ['ROLE_CLIENT'];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 49,
        minMessage: 'Name should contain between 3 and 50 characters.',
        maxMessage: 'Name should contain between 3 and 50 characters.',
    )]
    #[Assert\Regex(
        pattern: '/[^0-9a-zA-ZęółśążźćńĘÓŁŚĄŻŹĆŃ\'\s,.()-]/m',
        match: false,
        message: 'Company name contains invalid characters.',
        )]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 49,
        minMessage: 'Street should contain between 3 and 50 characters.',
        maxMessage: 'Street should contain between 3 and 50 characters.',
    )]
    #[Assert\Regex(
        pattern: '/[^0-9a-zA-ZęółśążźćńĘÓŁŚĄŻŹĆŃ\'\/\s,.()-]/m',
        match: false,
        message: 'Street contains invalid characters.',
        )]
    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 10,
        minMessage: 'Zip code should contain between 3 and 10 characters.',
        maxMessage: 'Zip code should contain between 3 and 10 characters.',
    )]
    #[Assert\Regex(
        pattern: '/[^0-9-]/m',
        match: false,
        message: 'Zip code contains invalid characters.',
        )]
    #[ORM\Column]
    private ?int $zipcode = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 49,
        minMessage: 'City should contain between 3 and 50 characters.',
        maxMessage: 'City should contain between 3 and 50 characters.',
    )]
    #[Assert\Regex(
        pattern: '/[^0-9a-zA-ZęółśążźćńĘÓŁŚĄŻŹĆŃ\'\/\s,.()-]/m',
        match: false,
        message: 'City contains invalid characters.',
        )]
    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 49,
        minMessage: 'State should contain between 3 and 50 characters.',
        maxMessage: 'State should contain between 3 and 50 characters.',
    )]
    #[Assert\Regex(
        pattern: '/[^0-9a-zA-ZęółśążźćńĘÓŁŚĄŻŹĆŃ\'\s,.()-]/m',
        match: false,
        message: 'State contains invalid characters.',
        )]
    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[Assert\Length(
        min: 3,
        max: 12,
        minMessage: 'TIN should contain between 3 and 12 characters.',
        maxMessage: 'TIN should contain between 3 and 12 characters.',
    )]
    #[Assert\Regex(
        pattern: '/[^0-9-]/m',
        match: false,
        message: 'Only digits allowed.',
        )]
    #[ORM\Column(nullable: true)]
    private ?int $TIN = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $currency = null;

    #[Assert\Length(
        min: 3,
        max: 49,
        minMessage: 'Surname should contain between 3 and 50 characters.',
        maxMessage: 'Surname should contain between 3 and 50 characters.',
    )]
    #[Assert\Regex(
        pattern: '/[^0-9a-zA-ZęółśążźćńĘÓŁŚĄŻŹĆŃ\'\s,.()-]/m',
        match: false,
        message: 'Surname contains invalid characters.',
        )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $surname = null;

    #[Assert\Length(
        min: 9,
        max: 12,
        minMessage: 'Telephone number should contain between 9 and 12 characters.',
        maxMessage: 'Telephone number should contain between 9 and 12 characters.',
    )]
    #[Assert\Regex(
        pattern: '/[^0-9-]/m',
        match: false,
        message: 'Only digits allowed.',
        )]
    #[ORM\Column(nullable: true)]
    private ?int $tel = null;

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
        $roles[] = '';

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getTIN(): ?int
    {
        return $this->TIN;
    }

    public function setTIN(int $TIN): self
    {
        $this->TIN = $TIN;

        return $this;
    }

    public function getcurrency(): ?string
    {
        return $this->currency;
    }

    public function setcurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(?int $tel): self
    {
        $this->tel = $tel;

        return $this;
    }
}
