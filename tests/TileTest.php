<?php

namespace App\Tests;

use App\Dto\Cube;
use App\Dto\Face;
use App\Enum\Color;
use App\Dto\Tile;
use PHPUnit\Framework\TestCase;

class TileTest extends TestCase
{
    public function testTileCreation(): void
    {
        $tile = new Tile(Color::Red, -1, -1);
        $this->assertInstanceOf(Tile::class, $tile);
        $this->assertSame(Color::Red, $tile->color);
        $this->assertSame(-1, $tile->x);
        $this->assertSame(-1, $tile->y);
    }

    
}
