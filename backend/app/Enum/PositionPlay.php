<?php

namespace App\Enum;

enum PositionPlay: Int
{
    case goalkeeper = 1;
    case defender = 2;
    case mid = 3;
    case atack = 4;

    public static function toArray(): array
    {
        return array_column(PositionPlay::cases(), 'value');
    }
}
