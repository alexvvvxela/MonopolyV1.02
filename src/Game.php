<?php

namespace Alexv\Monopoly;

class Game
{
    private Board $board;
    private array $players;
    private int $currentPlayerIndex;
    private GameState $gameState;
    private array $playerColors = [
        '#e74c3c',
        '#3498db',
        '#2ecc71',
        '#9b59b6'
    ];
    private array $playerPositions;

    public function __construct(array $playerNames)
    {
        $this->board = new Board();
        $this->players = [];
        $this->playerPositions = [];
        foreach ($playerNames as $index => $name) {
            $color = $this->playerColors[$index % count($this->playerColors)];
            $player = new Player($name, 2500, $color);
            $this->players[] = $player;
            $this->playerPositions[$name] = 0;
        }
        $this->currentPlayerIndex = 0;
        $this->gameState = new GameState();
    }


    public function getCurrentPlayer(): ?Player
    {
        return $this->players[$this->currentPlayerIndex] ?? null;
    }


    public function getBoard(): Board
    {
        return $this->board;
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function rollDice(): int
    {
        $dice1 = rand(1, 6);
        $dice2 = rand(1, 6);
        $diceResult = $dice1 + $dice2;
        $player = $this->getCurrentPlayer();
        $oldPosition = $player->getPosition();
        $newPosition = ($oldPosition + $diceResult) % $this->board->getTotalCells();
        $player->setPosition($newPosition);

        if ($newPosition < $oldPosition) {
            $player->addMoney(1000);
            $this->gameState->setMessage("{$player->getName()} прошел круг и получил +1000$.");
            $this->gameState->addLog("{$player->getName()} прошел круг и получил +1000$.");
        }
        $this->handleCellAction($player, $newPosition);
        if ($player->getMoney() <= 0) {
            $this->removePlayer($player);
            $this->gameState->setMessage("{$player->getName()} обанкротился!");
            $this->gameState->addLog("{$player->getName()} обанкротился!");
        }

        return $diceResult;
    }


    public function nextTurn(): void
    {
        if (count($this->players) > 0) {
            $this->currentPlayerIndex = ($this->currentPlayerIndex + 1) % count($this->players);
        }
    }

    public function buyProperty(): void
    {
        $currentPlayer = $this->getCurrentPlayer();
        $cell = $this->board->getCell($currentPlayer->getPosition());

        if ($cell->isOwnable() && empty($cell->getOwner())) {
            if ($currentPlayer->getMoney() >= $cell->getPrice()) {
                $currentPlayer->removeMoney($cell->getPrice());
                $cell->setOwner($currentPlayer->getName());
                $this->gameState->setMessage("{$currentPlayer->getName()} купил {$cell->getName()}");
                $this->gameState->addLog("{$currentPlayer->getName()} купил {$cell->getName()}");
            } else {
                $this->gameState->setMessage("Недостаточно денег для покупки {$cell->getName()}.");
                $this->gameState->addLog("Недостаточно денег для покупки {$cell->getName()}.");
            }
        }
    }


    public function getGameState(): GameState
    {
        return $this->gameState;
    }

    public function makeBotMove(bool &$botDiceRolled): void
    {
        $currentPlayer = $this->getCurrentPlayer();
        if (count($this->players) > 1) {
            if ($currentPlayer->getMoney() <= 0) {
                $this->removePlayer($currentPlayer);
                $this->gameState->setMessage("{$currentPlayer->getName()} обанкротился!");
                $this->gameState->addLog("{$currentPlayer->getName()} обанкротился!");
                $this->nextTurn();
                return;
            }
        }


        if (!$botDiceRolled) {
            if (count($this->players) > 1) {
                $this->gameState->addLog("Ход игрока {$currentPlayer->getName()}");
                $diceResult = $this->rollDice();
                $this->gameState->setMessage("{$currentPlayer->getName()} бросил кубик на $diceResult");
                $this->gameState->addLog("{$currentPlayer->getName()} бросил кубик на $diceResult");
                $cell = $this->board->getCell($currentPlayer->getPosition());
                if ($cell->isOwnable() && empty($cell->getOwner()) && $currentPlayer->getMoney() >= $cell->getPrice()) {
                    $this->buyProperty();
                } else {
                    $this->gameState->setMessage("{$currentPlayer->getName()} пропустил покупку");
                    $this->gameState->addLog("{$currentPlayer->getName()} пропустил покупку");
                }
            }

            $botDiceRolled = true;
        }
        $this->nextTurn();
    }

    private function handleCellAction(Player $player, int $position): void
    {
        $cell = $this->board->getCell($position);
        if ($cell->isOwnable() && !empty($cell->getOwner()) && $cell->getOwner() !== $player->getName()) {
            $owner = $this->getPlayerByName($cell->getOwner());
            if ($owner) {
                $rent = $cell->getRent();
                if ($player->getMoney() >= $rent) {
                    $player->removeMoney($rent);
                    $owner->addMoney($rent);
                    $this->gameState->setMessage("{$player->getName()} заплатил {$rent} ренты игроку {$owner->getName()} за {$cell->getName()}");
                    $this->gameState->addLog("{$player->getName()} заплатил {$rent} ренты игроку {$owner->getName()} за {$cell->getName()}");
                } else {
                    $this->gameState->setMessage("{$player->getName()} не может заплатить ренту за {$cell->getName()}");
                    $this->gameState->addLog("{$player->getName()} не может заплатить ренту за {$cell->getName()}");
                }
            }
        }
    }


    private function getPlayerByName(string $name): ?Player
    {
        foreach ($this->players as $player) {
            if ($player->getName() === $name) {
                return $player;
            }
        }
        return null;
    }
    private function removePlayer(Player $playerToRemove): void
    {
        $keyToRemove = array_search($playerToRemove, $this->players, true);
        if ($keyToRemove !== false) {
            unset($this->players[$keyToRemove]);
            $this->players = array_values($this->players);
        }
    }


    public function getWinner(): ?string
    {
        if (count($this->players) === 1) {
            return $this->players[0]->getName();
        }
        return null;
    }
}
