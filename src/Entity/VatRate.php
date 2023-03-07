<?php

namespace App\Entity;

use App\Repository\VatRateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['vatRate'], message: 'This vat rate is already exists.')]
#[ORM\Entity(repositoryClass: VatRateRepository::class)]
class VatRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[Assert\NotBlank]
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
    #[ORM\Column(unique: true)]
    private ?int $vatRate = null;

    public function getId(): ?int
    {
        return $this->id;
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
}
