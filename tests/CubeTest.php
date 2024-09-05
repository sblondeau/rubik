<?php

namespace App\Tests;

use App\Dto\Cube;
use App\Dto\Face;
use App\Dto\Tile;
use App\Enum\Color;
use App\Enum\FacePosition;
use App\Enum\TilePosition;
use PHPUnit\Framework\TestCase;

class CubeTest extends TestCase
{
    public function testCubeCreation(): void
    {
        $cube = new Cube();
        $this->assertInstanceOf(Cube::class, $cube);
        $this->assertInstanceOf(Face::class, $cube->getFace(FacePosition::Top));
        $this->assertSame(Color::Yellow, $cube->getFace(FacePosition::Top)->color);
        $this->assertSame(Color::White, $cube->getFace(FacePosition::Bottom)->color);
        $this->assertSame(Color::Blue, $cube->getFace(FacePosition::Right)->color);
        $this->assertSame(Color::Green, $cube->getFace(FacePosition::Left)->color);
        $this->assertSame(Color::Red, $cube->getFace(FacePosition::Front)->color);
        $this->assertSame(Color::Orange, $cube->getFace(FacePosition::Back)->color);
    }

    public function testRotateZAxis(): void
    {
        $cube = new Cube();
        $top = $cube->getFace(FacePosition::Top);
        $front = $cube->getFace(FacePosition::Front);
        $bottom = $cube->getFace(FacePosition::Bottom);
        $top->setTile(new Tile(Color::Green, 0, -1));
        $front->setTile(new Tile(Color::Yellow, 1, -1));
        $bottom->setTile(new Tile(Color::Blue, 0, -1));
        $this->assertSame(Color::Green, $top->getTile(TilePosition::TopCenter)->color);
        $this->assertSame(Color::Yellow, $front->getTile(TilePosition::TopRight)->color);

        $cube->rotateZ();

        $this->assertSame(Color::Red, $cube->getFace(FacePosition::Front)->color);
        $this->assertSame(Color::Orange, $cube->getFace(FacePosition::Back)->color);
        $this->assertSame(Color::Green, $cube->getFace(FacePosition::Top)->color);
        $this->assertSame(Color::Yellow, $cube->getFace(FacePosition::Right)->color);
        $this->assertSame(Color::Blue, $cube->getFace(FacePosition::Bottom)->color);
        $this->assertSame(Color::White, $cube->getFace(FacePosition::Left)->color);

        $right = $cube->getFace(FacePosition::Right);
        $left = $cube->getFace(FacePosition::Left);

        $this->assertSame(Color::Green, $right->getTile(TilePosition::Right)->color);
        $this->assertSame(Color::Blue, $left->getTile(TilePosition::Right)->color);
        $this->assertSame(Color::Yellow, $front->getTile(TilePosition::BottomRight)->color);
    }

    public function testRotateZAxisTwice(): void
    {
        $cube = new Cube();
        $cube->rotateZ();
        $cube->rotateZ();

        $this->assertSame(Color::Yellow, $cube->getFace(FacePosition::Bottom)->color);
        $this->assertSame(Color::Blue, $cube->getFace(FacePosition::Left)->color);
    }

    public function testRotateZAxisInverse(): void
    {
        $cube = new Cube();
        $cube->rotateZInverse();

        $this->assertSame(Color::Red, $cube->getFace(FacePosition::Front)->color);
        $this->assertSame(Color::Orange, $cube->getFace(FacePosition::Back)->color);
        $this->assertSame(Color::Green, $cube->getFace(FacePosition::Bottom)->color);
        $this->assertSame(Color::Yellow, $cube->getFace(FacePosition::Left)->color);
        $this->assertSame(Color::Blue, $cube->getFace(FacePosition::Top)->color);
        $this->assertSame(Color::White, $cube->getFace(FacePosition::Right)->color);
    }

    public function testRotateYRight(): void
    {
        $cube = new Cube();
        $front = $cube->getFace(FacePosition::Front);
        $top = $cube->getFace(FacePosition::Top);
        $bottom = $cube->getFace(FacePosition::Bottom);
        $right = $cube->getFace(FacePosition::Right);
        $top->setTile(new Tile(Color::Green, 0, -1));
        $bottom->setTile(new Tile(Color::Green, 0, -1));
        $front->setTile(new Tile(Color::Yellow, 1, -1));
        $cube->rotateY();

        $this->assertSame(Color::Red, $cube->getFace(FacePosition::Right)->color);
        $this->assertSame(Color::Orange, $cube->getFace(FacePosition::Left)->color);
        $this->assertSame(Color::Green, $cube->getFace(FacePosition::Front)->color);
        $this->assertSame(Color::Yellow, $cube->getFace(FacePosition::Top)->color);
        $this->assertSame(Color::Blue, $cube->getFace(FacePosition::Back)->color);
        $this->assertSame(Color::White, $cube->getFace(FacePosition::Bottom)->color);

        $this->assertSame(Color::Green, $top->getTile(TilePosition::Left)->color);
        $this->assertSame(Color::Green, $bottom->getTile(TilePosition::Right)->color);
        $right->setTile(new Tile(Color::Yellow, 1, -1));
    }

    public function testRotateYLeft(): void
    {
        $cube = new Cube();
        $front = $cube->getFace(FacePosition::Front);
        $top = $cube->getFace(FacePosition::Top);
        $bottom = $cube->getFace(FacePosition::Bottom);
        $left = $cube->getFace(FacePosition::Left);
        $top->setTile(new Tile(Color::Green, 0, -1));
        $front->setTile(new Tile(Color::Yellow, 1, -1));
        $bottom->setTile(new Tile(Color::Green, 0, -1));
        $cube->rotateYInverse();

        $this->assertSame(Color::Red, $cube->getFace(FacePosition::Left)->color);
        $this->assertSame(Color::Orange, $cube->getFace(FacePosition::Right)->color);
        $this->assertSame(Color::Green, $cube->getFace(FacePosition::Back)->color);
        $this->assertSame(Color::Yellow, $cube->getFace(FacePosition::Top)->color);
        $this->assertSame(Color::Blue, $cube->getFace(FacePosition::Front)->color);
        $this->assertSame(Color::White, $cube->getFace(FacePosition::Bottom)->color);

        $front->setTile(new Tile(Color::Yellow, 1, -1));
        $this->assertSame(Color::Green, $bottom->getTile(TilePosition::Left)->color);
        $left->setTile(new Tile(Color::Yellow, 1, -1));
    }

    public function testRotateXAxisTop(): void
    {
        $cube = new Cube();
        $left = $cube->getFace(FacePosition::Left);
        $left->setTile(new Tile(Color::Yellow, 0, -1));
        $this->assertSame(Color::Yellow, $left->getTile(TilePosition::TopCenter)->color);

        $cube->rotateX();

        $this->assertSame(Color::Red, $cube->getFace(FacePosition::Top)->color);
        $this->assertSame(Color::Orange, $cube->getFace(FacePosition::Bottom)->color);
        $this->assertSame(Color::Green, $cube->getFace(FacePosition::Left)->color);
        $this->assertSame(Color::Yellow, $cube->getFace(FacePosition::Back)->color);
        $this->assertSame(Color::Blue, $cube->getFace(FacePosition::Right)->color);
        $this->assertSame(Color::White, $cube->getFace(FacePosition::Front)->color);

        $this->assertSame(Color::Green, $left->getTile(TilePosition::TopCenter)->color);
        $this->assertSame(Color::Yellow, $left->getTile(TilePosition::Left)->color);
    }

    public function testRotateXAxisBottom(): void
    {
        $cube = new Cube();
        $cube->rotateXInverse();

        $this->assertSame(Color::Red, $cube->getFace(FacePosition::Bottom)->color);
        $this->assertSame(Color::Orange, $cube->getFace(FacePosition::Top)->color);
        $this->assertSame(Color::Green, $cube->getFace(FacePosition::Left)->color);
        $this->assertSame(Color::Yellow, $cube->getFace(FacePosition::Front)->color);
        $this->assertSame(Color::Blue, $cube->getFace(FacePosition::Right)->color);
        $this->assertSame(Color::White, $cube->getFace(FacePosition::Back)->color);
    }

    public function testTurnFrontFace(): void
    {
        $cube = new Cube();
        $top = $cube->getFace(FacePosition::Top);
        $left = $cube->getFace(FacePosition::Left);
        $right = $cube->getFace(FacePosition::Right);
        $bottom = $cube->getFace(FacePosition::Bottom);

        $this->assertSame(Color::Yellow, $top->getTile(TilePosition::BottomCenter)->color);
        $cube->turnFrontToRight();
        $this->assertSame(Color::Green, $top->getTile(TilePosition::BottomCenter)->color);
        $this->assertSame(Color::Yellow, $right->getTile(TilePosition::Left)->color);
        $this->assertSame(Color::Blue, $bottom->getTile(TilePosition::TopCenter)->color);
        $this->assertSame(Color::White, $left->getTile(TilePosition::Right)->color);
    }

    public function testTurnRightToTop(): void
    {
        $cube = new Cube();
        $cube->turnRightToTop();
        $top = $cube->getFace(FacePosition::Top);

        $this->assertSame(Color::Red, $top->getTile(TilePosition::Right)->color);
    }

    public function testTurnLeftToTop(): void
    {
        $cube = new Cube();
        $cube->turnLeftToTop();
        $top = $cube->getFace(FacePosition::Top);

        $this->assertSame(Color::Red, $top->getTile(TilePosition::Left)->color);
    }

    public function testTurnRightToBottom(): void
    {
        $cube = new Cube();
        $cube->turnRightToBottom();
        $bottom = $cube->getFace(FacePosition::Bottom);

        $this->assertSame(Color::Red, $bottom->getTile(TilePosition::Right)->color);
    }

    public function testTurnLeftToBottom(): void
    {
        $cube = new Cube();
        $cube->turnLeftToBottom();
        $top = $cube->getFace(FacePosition::Bottom);

        $this->assertSame(Color::Red, $top->getTile(TilePosition::Left)->color);
    }

    public function testTurnTopToRight(): void
    {
        $cube = new Cube();
        $cube->turnTopToRight();
        $top = $cube->getFace(FacePosition::Right);
        $back = $cube->getFace(FacePosition::Back);

        $this->assertSame(Color::Red, $top->getTile(TilePosition::TopCenter)->color);
        $this->assertSame(Color::Blue, $back->getTile(TilePosition::BottomCenter)->color);
    }

    public function testTurnTopToLeft(): void
    {
        $cube = new Cube();
        $cube->turnTopToLeft();
        $left = $cube->getFace(FacePosition::Left);
        $front = $cube->getFace(FacePosition::Front);

        $this->assertSame(Color::Red, $left->getTile(TilePosition::TopCenter)->color);
        $this->assertSame(Color::Green, $left->getTile(TilePosition::BottomCenter)->color);
        $this->assertSame(Color::Blue, $front->getTile(TilePosition::TopCenter)->color);
    }

    public function testTurnBottomToRight(): void
    {
        $cube = new Cube();
        $cube->turnBottomToRight();
        $right = $cube->getFace(FacePosition::Right);

        $this->assertSame(Color::Red, $right->getTile(TilePosition::BottomCenter)->color);
    }

    public function testTurnBottomToLeft(): void
    {
        $cube = new Cube();
        $cube->turnBottomToLeft();
        $top = $cube->getFace(FacePosition::Left);

        $this->assertSame(Color::Red, $top->getTile(TilePosition::BottomCenter)->color);
    }

    public function testShake()
    {
        $cube = new Cube();
        $cube2 = new $cube;

        $this->assertEquals($cube, $cube2);
        $cube->shake();

        $this->assertNotEquals($cube, $cube2);
    }
}
