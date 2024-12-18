<?php

namespace Alexv\Monopoly;

class Player
{
    private string $name;
    private int $money;
    private int $position;
    private string $color;

    public function __construct(string $name, int $initialMoney = 1500, string $color = '')
    {
        $this->name = $name;
        $this->money = $initialMoney;
        $this->position = 0;
        $this->color = $color;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMoney(): int
    {
        return $this->money;
    }

    public function addMoney(int $amount): void
    {
        $this->money += $amount;
    }

    public function removeMoney(int $amount): void
    {
        $this->money -= $amount;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }
}
