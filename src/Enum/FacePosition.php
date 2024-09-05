<?php

namespace App\Enum;

enum FacePosition: string
{
    case Top = 'top';
    case Left = 'left';
    case Right = 'right';
    case Bottom = 'bottom';
    case Back = 'back';
    case Front = 'front';

    public function coordinates(): array
    {
        return match ($this) {
            self::Top => [0, -1, 0],
            self::Bottom => [0, 1, 0],
            self::Right => [1, 0, 0],
            self::Left => [-1, 0, 0],
            self::Front => [0, 0, 1],
            self::Back => [0, 0, -1],
        };
    }

    public static function positionFromCoordinates(int $x, int $y, int $z): FacePosition
    {
        return match ([$x, $y, $z]) {
            [0, -1, 0] => self::Top,
            [0, 1, 0] => self::Bottom,
            [1, 0, 0] => self::Right,
            [-1, 0, 0] => self::Left,
            [0, 0, 1] => self::Front,
            [0, 0, -1] => self::Back,
        };
    }
    public function getLineTiles(): array
    {
        return match ($this) {
            self::Top => [TilePosition::BottomLeft, TilePosition::BottomCenter, TilePosition::BottomRight],
            self::Bottom => [TilePosition::TopLeft, TilePosition::TopCenter, TilePosition::TopRight],
            self::Right => [TilePosition::TopLeft, TilePosition::Left, TilePosition::BottomLeft],
            self::Left => [TilePosition::TopRight, TilePosition::Right, TilePosition::BottomRight],
            self::Front => [],
            self::Back => [],
        };
    }
}
