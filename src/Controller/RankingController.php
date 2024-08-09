<?php

namespace App\Controller;

use App\Entity\Ranking;
use App\Form\RankingType;
use App\Repository\RankingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ranking')]
class RankingController extends AbstractController
{
    #[Route('/', name: 'app_ranking_index', methods: ['GET'])]
    public function index(RankingRepository $rankingRepository): Response
    {
        return $this->render('ranking/index.html.twig', [
            'rankings' => $rankingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ranking_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ranking = new Ranking();
        $form = $this->createForm(RankingType::class, $ranking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ranking);
            $entityManager->flush();

            return $this->redirectToRoute('app_ranking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ranking/new.html.twig', [
            'ranking' => $ranking,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ranking_show', methods: ['GET'])]
    public function show(Ranking $ranking): Response
    {
        return $this->render('ranking/show.html.twig', [
            'ranking' => $ranking,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ranking_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ranking $ranking, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RankingType::class, $ranking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ranking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ranking/edit.html.twig', [
            'ranking' => $ranking,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ranking_delete', methods: ['POST'])]
    public function delete(Request $request, Ranking $ranking, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ranking->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ranking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ranking_index', [], Response::HTTP_SEE_OTHER);
    }
}
