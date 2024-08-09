<?php

namespace App\Controller;

use App\Entity\Athlete;
use App\Repository\AthleteRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Intl\Countries;

class AthleteController extends AbstractController
{
    #[Route('/athlete', name: 'app_athlete')]
    public function index(): Response
    {
        return $this->render('athlete/index.html.twig', [
            'entity' => Athlete::class,
            'headers' => [],
            'searchFields' => ['id'], 
        ]);
    }
}
