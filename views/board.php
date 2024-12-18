<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Монополия</title>
    <link rel="stylesheet" href="/style.css">
</head>

<body>
    <h1>Монополия V1.02</h1>
    <a href="?reset=true">Сбросить игру</a>

    <div id="message">
        <p><?= $gameState->getMessage() ?></p>
    </div>


    <div class="board">
        <?php foreach ($board->getCells() as $index => $cell): ?>
            <div class="cell <?= $cell->getType() ?>" data-cell-index="<?= $index ?>" style="<?= $cell->getOwner() ? 'background-color: ' . (array_filter($players, function ($player) use ($cell) {
                                                                                                    return $player->getName() === $cell->getOwner();
                                                                                                }) ? array_values(array_filter($players, function ($player) use ($cell) {
                                                                                                    return $player->getName() === $cell->getOwner();
                                                                                                }))[0]->getColor() : '') . '; filter: hue-rotate(30deg); display: flex; justify-content: center; align-items: center;'  : 'display: flex; justify-content: center; align-items: center;' ?>">

                <?php
                $playersOnCell = array_filter($players, function ($player) use ($index, $cell) {
                    return $player->getPosition() === $index && ($cell->getOwner() !== $player->getName());
                });
                foreach ($playersOnCell as $player):
                ?>
                    <div class="player" data-player-name="<?= $player->getName() ?>" style="background-color: <?= $player->getColor() ?>"></div>
                <?php endforeach; ?>

                <div style="position: absolute; top: 0; left: 0; width: 100%; padding: 5px; box-sizing: border-box;">
                    <p style="margin:0;"><?= $cell->getName() ?></p>
                    <?php if ($cell->isOwnable()): ?>
                        <p style="margin:0;">Цена: <?= $cell->getPrice() ?></p>
                        <p style="margin:0;">Аренда: <?= $cell->getRent() ?></p>
                    <?php endif; ?>
                    <?php if ($cell->getOwner()): ?>
                        <p style="margin:0;">Владелец: <?= $cell->getOwner() ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <form method="post">
        <?php if (count($players) > 1) : ?>
            <button type="submit" name="roll">Бросить кубик</button>
            <button type="submit" name="buy">Купить</button>
            <button type="submit" name="end_turn">Завершить ход</button>
        <?php endif; ?>
    </form>

    <div class="log">
        <h1>Журнал</h1>
        <?php foreach ($gameState->getLogs() as $log) : ?>
            <p><?= $log ?></p>
        <?php endforeach; ?>
    </div>

    <h1>Игроки</h1>
    <?php foreach ($players as $player): ?>
        <p><?= $player->getName() ?>: $<?= $player->getMoney() ?></p>
    <?php endforeach; ?>
    <?php
    $winner = $game->getWinner();
    if ($winner) {
        echo "<p style='font-size: 2em; color: green; text-align: center'>Победитель: {$winner}!</p>";
    }
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const board = document.querySelector('.board');
            const cells = board.querySelectorAll('.cell');

            function updatePlayerPositions() {

            }

            updatePlayerPositions();

        });
    </script>
</body>

</html>