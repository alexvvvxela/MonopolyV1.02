<?php

namespace Alexv\Monopoly;

class Board
{
    private array $cells;

    public function __construct()
    {
        $this->cells = [
            new Cell("Старт", "start"),
            new Cell("Площадь Марльборо", "property", 260, 120),
            new Cell("ПустойХод", "chance"),
            new Cell("Площадь Оксфорд-стрит", "property", 360, 220),
            new Cell("Налог", "tax"),
            new Cell("Железная дорога Ливерпуль-стрит", "railway", 400, 250),
            new Cell("Площадь Регент-стрит", "property", 100, 60),
            new Cell("ПустойХод", "chance"),
            new Cell("Площадь Св. Джеймса", "property", 100, 60),
            new Cell("Площадь Пикадилли", "property", 520, 400),
            new Cell("Тюрьма", "jail"),
            new Cell("Площадь Ковентри-стрит", "property", 440, 300),
            new Cell("Электростанция", "utility", 550, 400),
            new Cell("Площадь Лейчестер", "property", 440, 260),
            new Cell("Площадь Странд", "property", 460, 300),
            new Cell("Железная дорога Мэрилебон", "railway", 500, 100),
            new Cell("Центральная улица 52", "property", 5252, 5252),
            new Cell("ПустойХод", "chance"),
            new Cell("Площадь Трафальгар", "property", 500, 240),
            new Cell("Улица Красных Фонарей", "property", 200, 160),
            new Cell("Бесплатная парковка", "free"),
            new Cell("Площадь Нью-Оксфорд-стрит", "property", 270, 180),
            new Cell("ПустойХод", "chance"),
            new Cell("Площадь Кингс-кросс", "property", 220, 180),
            new Cell("Площадь Юстон-роуд", "property", 240, 200),
            new Cell("Железная дорога Фенчерч-стрит", "railway", 2000, 1000),
            new Cell("Площадь Боу-стрит", "property", 600, 400),
            new Cell("Площадь Мальборо", "property", 460, 220),
            new Cell("Станция Starlink ", "utility", 1500, 1000),
            new Cell("Площадь Риджент", "property", 480, 240),
            new Cell("Отправляйтесь в тюрьму", "gotojail"),
            new Cell("Площадь Мэйфэр", "property", 350, 200),
            new Cell("ПустойХод", "chance"),
            new Cell("Площадь Парк-Лейн", "property", 400, 300)
        ];
    }

    public function getCell(int $index): Cell
    {
        return $this->cells[$index];
    }
    public function getTotalCells(): int
    {
        return count($this->cells);
    }

    public function getCells(): array
    {
        return $this->cells;
    }
}
