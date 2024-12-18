<?php
require __DIR__ . '/../vendor/autoload.php';

use Alexv\Monopoly\Game;

session_start();

if (isset($_GET['reset'])) {
    session_unset();
    session_destroy();
    header('Location: /');
    exit;
}


if (!isset($_SESSION['game'])) {
    $playerNames = ['Игрок 1', 'Бот 1'];
    $_SESSION['game'] = new Game($playerNames);
    $_SESSION['bots_set'] = true;
}
if (!isset($_SESSION['bots_set'])) {
    include __DIR__ . '/../views/setup.php';
    exit;
}
$game = $_SESSION['game'];
$board = $game->getBoard();
$currentPlayer = $game->getCurrentPlayer();
$gameState = $game->getGameState();

$diceRolled = isset($_SESSION['diceRolled']) ? $_SESSION['diceRolled'] : false;

if (isset($_POST['roll'])) {
    if (!$diceRolled) {
        $diceResult = $game->rollDice();
        $cell = $board->getCell($currentPlayer->getPosition());
        $gameState->setMessage("{$currentPlayer->getName()} бросил кубик на $diceResult. Перешел на {$cell->getName()}");
        $gameState->addLog("{$currentPlayer->getName()} бросил кубик на $diceResult. Перешел на {$cell->getName()}");

        $_SESSION['diceRolled'] = true;
    } else {
        $gameState->setMessage("Кубик уже брошен. Пожалуйста, завершите ход.");
        $gameState->addLog("Кубик уже брошен. Пожалуйста, завершите ход.");
    }
}

if (isset($_POST['buy'])) {
    $cell = $board->getCell($currentPlayer->getPosition());
    if ($cell->isOwnable() && empty($cell->getOwner())) {
        if ($currentPlayer->getMoney() >= $cell->getPrice()) {
            $currentPlayer->removeMoney($cell->getPrice());
            $cell->setOwner($currentPlayer->getName());
            $gameState->setMessage("{$currentPlayer->getName()} купил {$cell->getName()} за {$cell->getPrice()}");
            $gameState->addLog("{$currentPlayer->getName()} купил {$cell->getName()} за {$cell->getPrice()}");
        } else {
            $gameState->setMessage("Недостаточно денег для покупки {$cell->getName()}.");
            $gameState->addLog("Недостаточно денег для покупки {$cell->getName()}.");
        }
    } else {
        $gameState->setMessage("Невозможно купить эту собственность.");
        $gameState->addLog("Невозможно купить эту собственность.");
    }
}

if (isset($_POST['end_turn'])) {
    $_SESSION['diceRolled'] = false;
    $game->nextTurn();
    $botDiceRolled = false;
    while ($currentPlayer = $game->getCurrentPlayer()) {
        if ($currentPlayer->getName() !== "Игрок 1") {
            $game->makeBotMove($botDiceRolled);
            $currentPlayer = $game->getCurrentPlayer();
        } else {
            break;
        }
    }
    $currentPlayer = $game->getCurrentPlayer();
    $gameState->setMessage("Ход игрока {$currentPlayer->getName()} завершён.");
    $gameState->addLog("Ход игрока {$currentPlayer->getName()} завершён.");
}


$players = $game->getPlayers();
include __DIR__ . '/../views/board.php';
$gameState->clearLogs();
