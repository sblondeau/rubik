<?php

namespace App\Twig\Components;

use App\Dto\Cube as DtoCube;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
final class Cube
{
    use DefaultActionTrait;
    use ComponentToolsTrait;
    
    #[LiveProp(fieldName: 'data', writable:true, useSerializerForHydration:true)]
    public DtoCube $cube;
   
    public function mount(DtoCube $cube)
    {
        $this->cube = $cube;
        $this->cube->init();
    }

    #[LiveAction]
    public function shake()
    {
        $this->cube->shake();
    }

   
    private function rotate(string $direction)
    {
        match($direction) {
            'x' => $this->cube->rotateX(),
            'xInverse' => $this->cube->rotateXInverse(),
            'y' => $this->cube->rotateY(),
            'yInverse' => $this->cube->rotateYInverse(),
            'z' => $this->cube->rotateZ(),
            'zInverse' => $this->cube->rotateZInverse(),
        };
        sleep(1);
        
    }
    
    #[LiveAction]
    public function rotateX()
    {
        $this->rotate('x');       
    }
    #[LiveAction]
    public function rotateXInverse()
    {
        $this->rotate('xInverse');       
    }
    
    #[LiveAction]
    public function rotateY()
    {
        $this->rotate('y');       
    }
    #[LiveAction]
    public function rotateYInverse()
    {
        $this->rotate('yInverse');       
    }
    
    #[LiveAction]
    public function rotateZ()
    {
        $this->rotate('z');       
    }
    #[LiveAction]
    public function rotateZInverse()
    {
        $this->rotate('zInverse');       
    }

    #[LiveAction]
    public function turn (#[LiveArg()] string $direction)
    {
        match($direction) {
            'frontToRight' => $this->cube->turnFrontToRight(),
            'frontToLeft' => $this->cube->turnFrontToLeft(),
            'topToRight' => $this->cube->turnTopToRight(),
            'topToLeft' => $this->cube->turnTopToLeft(),
            'bottomToRight' => $this->cube->turnBottomToRight(),
            'bottomToLeft' => $this->cube->turnBottomToLeft(),
            'rightToTop' => $this->cube->turnRightToTop(),
            'rightToBottom' => $this->cube->turnRightToBottom(),
            'leftToTop' => $this->cube->turnLeftToTop(),
            'leftToBottom' => $this->cube->turnLeftToBottom(),
        };
        
    }
}
