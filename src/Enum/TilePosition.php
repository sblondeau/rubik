<?php

namespace App\Enum;

enum TilePosition
{
    case TopLeft;
    case TopCenter;
    case TopRight;
    case Left;
    case Center;
    case Right;
    case BottomLeft;
    case BottomCenter;
    case BottomRight;

    public function coordinates(): array
    {
        return match($this) {
            self::TopLeft => [-1, -1],
            self::TopCenter => [0, -1],
            self::TopRight => [1, -1],
            self::Left => [-1, 0],
            self::Center => [0, 0],
            self::Right => [1, 0], 
            self::BottomLeft => [-1, 1], 
            self::BottomCenter => [0, 1], 
            self::BottomRight => [1, 1], 
        };
    }
}