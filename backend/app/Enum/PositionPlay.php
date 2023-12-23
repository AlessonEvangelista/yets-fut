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

    public static function getById(int $id): string
    {
        $name = '';
        foreach (self::cases() as $value) {
            if ($value->value === $id) {
                $name = $value->name;
            }
        }

        return $name;
    }
}
