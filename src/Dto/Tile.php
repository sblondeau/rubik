<?php

namespace App\Dto;

use App\Enum\Color;

class Tile
{
    public function __construct(public Color $color,  public int $x, public int $y)
    {
        
    }
}
