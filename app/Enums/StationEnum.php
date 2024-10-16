<?php

namespace App\Enums;

enum StationEnum: int
{
    case A = 0;
    case B = 1;

    public function getName(): string
    {
        return match ($this) {
            self::A => 'A',
            self::B => 'B',
        };
    }

    public function getOppositeStation(): self
    {
        return match ($this) {
            self::A => self::B,
            self::B => self::A,
        };
    }
}
