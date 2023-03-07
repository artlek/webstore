<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['code'], message: 'There is already product with this product code.')]
#[UniqueEntity(fields: ['name'], message: 'There is already product with this product name.')]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Type(
        type: 'integer',
        message: 'Only numbers between 0 and 50 (and dash mark) allowed.',
        )]
        #[Assert\LessThan(
            value: 1000000000,
            message: 'Max. 9 characters.',
            )]
        #[Assert\GreaterThan(
            value: 0,
            message: 'Only integer numbers greater than zero.'
            )]
    #[ORM\Column(length: 255, unique: true)]
    private ?int $code = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Product name should contain between 3 and 50 characters.',
        maxMessage: 'Product name should contain between 3 and 50 characters.',
    )]
    #[Assert\Regex(
        pattern: '/[^0-9a-zA-ZęółśążźćńĘÓŁŚĄŻŹĆŃ\s,.()-]/m',
        match: false,
        message: 'Product name contains invalid characters.',
        )]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[Assert\Type('float')]
    #[Assert\LessThan(
        value: 1000000000,
        message: 'Only float numbers less than 1 bilion.',
        )]
    #[Assert\GreaterThanOrEqual(
        value: 0,
        message: 'Only float numbers greater than zero (or zero).'
        )]
    #[ORM\Column]
    private ?float $price = null;

    #[Assert\Length(
        min: 1,
        max: 20,
        minMessage: 'Min. 1 character.',
        maxMessage: 'Max. 20 characters.',
    )]
    #[Assert\Regex(
        pattern: '/[^0-9a-zA-ZęółśążźćńĘÓŁŚĄŻŹĆŃ\s,.()-]/m',
        match: false,
        message: 'Unit contains invalid characters.',
        )]
    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $unit = null;

    #[Assert\Type(
        type: 'integer',
        message: 'Only numbers between 0 and 100 allowed.',
        )]
    #[Assert\LessThanOrEqual(
        value: 100,
        message: 'Only numbers between 0 and 100 allowed.',
        )]
    #[Assert\GreaterThanOrEqual(
        value: 0,
        message: 'Only numbers between 0 and 100 allowed.')]
    #[Assert\NotBlank]
    #[ORM\Column]
    private ?int $vatRate = null;

    #[ORM\Column]
    private ?bool $blocked = false;

    #[ORM\Column]
    private ?bool $hasPhoto = null;

    #[Assert\Regex(
        pattern: '/[^0-9a-zA-ZęółśążźćńĘÓŁŚĄŻŹĆŃ\s,.()-]/m',
        match: false,
        message: 'Description contains invalid characters.',
        )]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 1000,
        minMessage: 'Min. 3 characters.',
        maxMessage: 'Max. 1000 characters.',
    )]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $currency = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getVatRate(): ?int
    {
        return $this->vatRate;
    }

    public function setVatRate(int $vatRate): self
    {
        $this->vatRate = $vatRate;

        return $this;
    }

    public function isBlocked(): ?bool
    {
        return $this->blocked;
    }

    public function setBlocked(bool $blocked): self
    {
        $this->blocked = $blocked;

        return $this;
    }

    public function isHasPhoto(): ?bool
    {
        return $this->hasPhoto;
    }

    public function setHasPhoto(bool $hasPhoto): self
    {
        $this->hasPhoto = $hasPhoto;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}
