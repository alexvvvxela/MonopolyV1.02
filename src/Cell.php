<?php

namespace Alexv\Monopoly;

class Cell
{
    private string $name;
    private string $type;
    private int $price;
    private int $rent;
    private ?string $owner;


    public function __construct(string $name, string $type, int $price = 0, int $rent = 0)
    {
        $this->name = $name;
        $this->type = $type;
        $this->price = $price;
        $this->rent = $rent;
        $this->owner = null;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }


    public function isOwnable(): bool
    {
        return $this->type === 'property' || $this->type === 'railway' || $this->type === 'utility';
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getRent(): int
    {
        return $this->rent;
    }
    public function setOwner(?string $owner): void
    {
        $this->owner = $owner;
    }
    public function getOwner(): ?string
    {
        return $this->owner;
    }
}
