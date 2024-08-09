<?php

namespace App\Controller;

use App\Entity\Ranking;
use App\Repository\RankingRepository;
use Symfony\Component\Intl\Countries;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(RankingRepository $rankingRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'entity' => Ranking::class,
            'headers' => [],
            'searchFields' => [],
        ]);
    }
}
