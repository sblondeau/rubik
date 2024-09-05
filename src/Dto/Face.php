<?php

namespace App\Dto;

use App\Enum\Color;
use App\Enum\FacePosition;
use App\Enum\TilePosition;

class Face
{
    public const LINES = 3;

    public function __construct(
        public Color $color,
        public FacePosition $position,
        public array $tiles = [],
    ) {

        $this->init();
        
    }

    public function init()
    {
        $shift = (self::LINES - 1) / 2;
        $i = 0;

        for ($tileX = -$shift; $tileX <= $shift; $tileX++) {
            for ($tileY = -$shift; $tileY <= $shift; $tileY++) {
                $this->tiles[$i] = new Tile(
                    Color::tryFrom($this->tiles[$i]['color']?? $this->color->value) , $this->tiles[$i]['x'] ?? $tileX, $this->tiles[$i]['y'] ?? $tileY);
                $i++;
            }
        }
        
    }
    public function getTile(TilePosition $position): Tile
    {
        return $this->getTileCoordinates(...$position->coordinates());
    }

    private function getTileCoordinates(int $x, int $y): Tile
    {
        foreach ($this->tiles as $tile) {
            if ($x === $tile->x && $y === $tile->y) {
                return $tile;
            }
        }
    }

    public function getLineTiles(): array
    {
        $tilePositions = $this->position->getLineTiles();
        foreach ($tilePositions as $tilePosition) {
            $tiles[] = $this->getTile($tilePosition);
        }

        return $tiles ?? [];
    }

    public function setTile(Tile $newTile): void
    {
        foreach ($this->tiles as $i => $tile) {
            if ([$newTile->x, $newTile->y] === [$tile->x, $tile->y]) {
                $this->tiles[$i] = $newTile;
                return;
            }
        }
    }

    public function setTiles(array $tiles): void
    {
        foreach ($tiles as $tile) {
            if(is_array($tile)) {
                $tile = new Tile(Color::tryFrom($tile['color']), $tile['x'], $tile['y']);;
            }
            $this->setTile($tile);
        }
    }

    public function turn(array $matrixX, array $matrixY): void
    {
        foreach ($this->tiles as $tile) {
            $initX = $tile->x;
            $initY = $tile->y;
            $tile->x = $initX * $matrixX[0] + $initY * $matrixX[1];
            $tile->y = $initX * $matrixY[0] + $initY * $matrixY[1];
        }
    }

    // Turn face clockwise
    public function turnRight()
    {
        $this->turn(
            [0, -1],
            [1, 0],
        );
    }

    // Turn face anticlockwise
    public function turnLeft()
    {
        $this->turn(
            [0, 1],
            [-1, 0],
        );
    }
}
