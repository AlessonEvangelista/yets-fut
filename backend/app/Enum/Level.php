<?php

namespace App\Enum;

enum Level: Int
{
    case much_bad = 1;
    case bad = 2;
    case ok = 3;
    case good = 4;
    case sso_good = 5;

    public static function toArray(): array
    {
        return array_column(Level::cases(), 'value');
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
