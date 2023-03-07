<?php

namespace App\Entity;

use App\Repository\UnitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['fullName'], message: 'There is already unit with this full name.')]
#[UniqueEntity(fields: ['shortName'], message: 'There is already unit with this short name.')]
#[ORM\Entity(repositoryClass: UnitRepository::class)]
class Unit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 20,
        minMessage: 'Min. 3 characters.',
        maxMessage: 'Max. 20 characters.',
    )]
    #[Assert\Regex(
        pattern: '/[^0-9a-zA-ZęółśążźćńĘÓŁŚĄŻŹĆŃ\s,.()-]/m',
        match: false,
        message: 'Full name of unit contains invalid characters.',
        )]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $fullName = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 1,
        max: 20,
        minMessage: 'Min. 1 characters.',
        maxMessage: 'Max. 20 characters.',
    )]
    #[Assert\Regex(
        pattern: '/[^0-9a-zA-ZęółśążźćńĘÓŁŚĄŻŹĆŃ\s,.()-]/m',
        match: false,
        message: 'Short name of unit contains invalid characters.',
        )]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $shortName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }
}
