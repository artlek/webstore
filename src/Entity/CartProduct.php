<?php

namespace App\Entity;

use App\Repository\CartRepository;

class CartProduct
{
    public function __construct()
    {
        $this->code = $_POST['code'];
        $this->name = $_POST['name'];
        $this->price = $_POST['price'];
        $this->unit = $_POST['unit'];
        $this->vatrate = $_POST['vatrate'];
        $this->quantity = $_POST['quantity'];
        $this->total = $this->price * $this->quantity;
    }

    private ?int $id = null;

    private ?int $code = null;

    private ?string $name = null;

    private ?float $price = null;

    private ?string $unit = null;

    private ?int $vatrate = null;

    private ?int $quantity = null;

    private array $productArray;

    private float $total;

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

    public function getVatrate(): ?int
    {
        return $this->vatrate;
    }

    public function setVatrate(int $vatrate): self
    {
        $this->vatrate = $vatrate;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        
        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;
        
        return $this;
    }
}
