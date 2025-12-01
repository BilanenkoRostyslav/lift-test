<?php

namespace App\Enum;

enum OrderDirection: string
{
    case ASC = "ASC";
    case DESC = "DESC";

    public static function values(): array
    {
        return array_map(fn($value) => $value->value, self::cases());
    }
}
