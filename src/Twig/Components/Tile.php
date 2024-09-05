<?php

namespace App\Twig\Components;

use App\Dto\Tile as DtoTile;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Tile
{
    #[LiveProp(updateFromParent: true, useSerializerForHydration: true)]
    public DtoTile $tile; 
}
