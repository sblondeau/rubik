<?php

namespace App\Dto;

use App\Dto\Face;
use App\Enum\Color;
use App\Enum\FacePosition;

class Cube
{
    public ?array $faces;
    public function __construct(?array $faces = null)
    {
        if ($faces) {
            $this->faces = $faces;
        } else {
            $this->init();
        }

        foreach ($this->faces as $key => $face) {
            if (is_array($face)) {
                $this->faces[$key] = new Face(Color::tryFrom($face['color']), FacePosition::tryFrom($face['position']), $face['tiles']);
            }
        }
    }

    public function init(): void
    {
        $this->faces[0] = new Face(Color::Yellow, FacePosition::Top);
        $this->faces[1] = new Face(Color::White, FacePosition::Bottom);
        $this->faces[2] = new Face(Color::Blue, FacePosition::Right);
        $this->faces[3] = new Face(Color::Green, FacePosition::Left);
        $this->faces[4] = new Face(Color::Red, FacePosition::Front);
        $this->faces[5] = new Face(Color::Orange, FacePosition::Back);
    }

    public function shake(): void
    {
        $moves = rand(50, 100);
        $functions = [
            'turnRightToTop',
            'turnRightToBottom',
            'turnLeftToTop',
            'turnLeftToBottom',
            'turnTopToRight',
            'turnTopToLeft',
            'turnBottomToRight',
            'turnBottomToLeft',
        ];

        for ($i = 0; $i < $moves; $i++) {
            $method = $functions[array_rand($functions)];
            $this->$method();
        }
    }

    public function getFace(FacePosition $position): Face
    {
        foreach ($this->faces as $face) {
            if ($face->position === $position) {
                return $face;
            }
        }
    }

    private function rotate(array $matrixX, array $matrixY, array $matrixZ): void
    {
        foreach ($this->faces as $face) {
            [$initX, $initY, $initZ] = $face->position?->coordinates();
            $x = $initX * $matrixX[0] + $initY * $matrixX[1] + $initZ * $matrixX[2];
            $y = $initX * $matrixY[0] + $initY * $matrixY[1] + $initZ * $matrixY[2];
            $z = $initX * $matrixZ[0] + $initY * $matrixZ[1] + $initZ * $matrixZ[2];
            $face->position = FacePosition::positionFromCoordinates($x, $y, $z);
        }
    }

    // Rotation around Z axis clockwise (top to right)
    public function rotateZ()
    {
        $this->rotate(
            [0, -1, 0],
            [1, 0, 0],
            [0, 0, 1]
        );

        foreach(FacePosition::cases() as $facePosition) {
            $face  = $this->getFace($facePosition);
            $face->turnRight();
        }

    }

    // Rotation around Z axis anticlockwise (top to left)
    public function rotateZInverse()
    {
        $this->rotate(
            [0, 1, 0],
            [-1, 0, 0],
            [0, 0, 1]
        );

        foreach(FacePosition::cases() as $facePosition) {
            $face  = $this->getFace($facePosition);
            $face->turnLeft();
        }
    }

    // Rotation around Y axis clockwise (front to right)
    public function rotateY()
    {
        $back = $this->getFace(FacePosition::Back);
        $back->turnRight();
        $back->turnRight(); 
        $this->rotate(
            [0, 0, 1],
            [0, 1, 0],
            [-1, 0, 0]
        );

        $top = $this->getFace(FacePosition::Top);
        $bottom = $this->getFace(FacePosition::Bottom);
        $back = $this->getFace(FacePosition::Back);
        $back->turnRight();
        $back->turnRight(); 
        $top->turnLeft();
        $bottom->turnRight();
    }

    // Rotation around Y axis conter anticlockwise (front to left)
    public function rotateYInverse()
    {

        $this->rotateY();
        $this->rotateY();
        $this->rotateY();
    }

    // Rotation around X axis front to top
    public function rotateX()
    {
        $this->rotate(
            [1, 0, 0],
            [0, 0, -1],
            [0, 1, 0]
        );

        $right = $this->getFace(FacePosition::Right);
        $left = $this->getFace(FacePosition::Left);
        $left->turnLeft();
        $right->turnRight();
    }

    // Rotation around X axis front to bottom
    public function rotateXInverse()
    {
        $this->rotate(
            [1, 0, 0],
            [0, 0, 1],
            [0, -1, 0]
        );

        $right = $this->getFace(FacePosition::Right);
        $left = $this->getFace(FacePosition::Left);
        $left->turnRight();
        $right->turnLeft();
    }

    public function turnFrontToRight(): void
    {
        $front = $this->getFace(FacePosition::Front);
        $front->turnRight();

        $this->turnAdjacentTiles();
    }

    public function turnFrontToLeft(): void
    {
        $this->turnFrontToRight();
        $this->turnFrontToRight();
        $this->turnFrontToRight();
    }

    private function turnAdjacentTiles(): void
    {
        $left = $this->getFace(FacePosition::Left);
        $top = $this->getFace(FacePosition::Top);
        $right = $this->getFace(FacePosition::Right);
        $bottom = $this->getFace(FacePosition::Bottom);

        $leftTiles = $left->getLineTiles();
        $topTiles = $top->getLineTiles();
        $rightTiles = $right->getLineTiles();
        $bottomTiles = $bottom->getLineTiles();

        $newLeft = $this->turnLine($bottomTiles);
        $newBottom = $this->turnLine($rightTiles);
        $newRight = $this->turnLine($topTiles);
        $newTop = $this->turnLine($leftTiles);

        $left->setTiles($newLeft);
        $bottom->setTiles($newBottom);
        $right->setTiles($newRight);
        $top->setTiles($newTop);
    }

    private function turnLine(array $tiles): array
    {
        foreach ($tiles as $tile) {
            $initX = $tile->x;
            $initY = $tile->y;

            $x = $initY * -1; //matrix rotaiton 90 deg
            $y = $initX;

            $newTiles[] = new Tile($tile->color, $x, $y);
        }

        return $newTiles ?? [];
    }

    public function turnRightToTop()
    {
        $this->rotateYInverse();
        $this->turnFrontToRight();

        $this->rotateY();
    }

    public function turnRightToBottom()
    {
        $this->rotateYInverse();
        $this->turnFrontToRight();
        $this->turnFrontToRight();
        $this->turnFrontToRight();

        $this->rotateY();
    }

    public function turnLeftToTop()
    {
        $this->rotateY();
        $this->turnFrontToRight();
        $this->turnFrontToRight();
        $this->turnFrontToRight();

        $this->rotateYInverse();
    }

    public function turnLeftToBottom()
    {
        $this->rotateY();
        $this->turnFrontToRight();

        $this->rotateYInverse();
    }

    public function turnTopToRight()
    {
        $this->rotateXInverse();
        $this->turnFrontToLeft();
        $this->rotateX();

    }

    public function turnTopToLeft()
    {
        $this->turnTopToRight();
        $this->turnTopToRight();
        $this->turnTopToRight();
    }

    public function turnBottomToRight()
    {
        $this->rotateX();
        $this->turnFrontToRight();
        $this->rotateXInverse();
    }

    public function turnBottomToLeft()
    {
        $this->turnBottomToRight();
        $this->turnBottomToRight();
        $this->turnBottomToRight();
    }
}
