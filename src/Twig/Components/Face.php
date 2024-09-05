<?php

namespace App\Twig\Components;

use App\Dto\Face as DtoFace;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
final class Face
{
    use DefaultActionTrait;

    #[LiveProp(updateFromParent: true, useSerializerForHydration: true)]
    public DtoFace $face;
}
