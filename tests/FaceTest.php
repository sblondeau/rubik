<?php

namespace App\Tests;

use App\Dto\Cube;
use App\Dto\Face;
use App\Dto\Tile;
use App\Enum\Color;
use App\Enum\FacePosition;
use App\Enum\TilePosition;
use PHPUnit\Framework\TestCase;

class FaceTest extends TestCase
{
    public function testFaceCreation(): void
    {
        $face = new Face(Color::Blue, FacePosition::Right);
        $this->assertInstanceOf(Face::class, $face);
        $this->assertCount(9, $face->tiles);
        $this->assertSame(Color::Blue, $face->color);
        $this->assertSame('blue', $face->color->value);
    }

    public function testFaceTiles(): void
    {
        $face = new Face(Color::Blue, FacePosition::Right);
        $this->assertInstanceOf(Tile::class, $face->tiles[0]);
        $this->assertSame(Color::Blue, $face->tiles[5]->color);
        $this->assertSame(FacePosition::Right, $face->position);
        $this->assertSame([1, 0, 0], FacePosition::Right->coordinates());
    }

   
    public function testSetTile(): void 
    {
        $face = new Face(Color::Blue, FacePosition::Right);
        $face->setTile(new Tile(Color::Red, 1, -1));
        $this->assertSame(Color::Blue, $face->getTile(TilePosition::TopLeft)->color);
        $this->assertSame(Color::Red, $face->getTile(TilePosition::TopRight)->color);
    }

    public function testTurnRightFace()
    {
        $face = new Face(Color::Blue, FacePosition::Right);
        $face->setTile(new Tile(Color::Green, 0, -1));
        $face->setTile(new Tile(Color::Red, 1, -1));
        $face->turnRight();
        $this->assertSame(Color::Green, $face->getTile(TilePosition::Right)->color);
        $this->assertSame(Color::Red, $face->getTile(TilePosition::BottomRight)->color);
    }

    public function testTurnLeftFace()
    {
        $face = new Face(Color::Blue, FacePosition::Right);
        $face->setTile(new Tile(Color::Green, 0, -1));
        $face->setTile(new Tile(Color::Red, 1, -1));
        $face->turnLeft();
        $this->assertSame(Color::Green, $face->getTile(TilePosition::Left)->color);
        $this->assertSame(Color::Red, $face->getTile(TilePosition::TopLeft)->color);
    }
}
