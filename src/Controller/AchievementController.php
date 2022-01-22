<?php

namespace App\Controller;

use App\Entity\Achievement;
use App\Form\AchievementType;
use App\Repository\AchievementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/achievement')]
class AchievementController extends AbstractController
{
    #[Route('/', name: 'achievement_index', methods: ['GET'])]
    public function index(AchievementRepository $achievementRepository): Response
    {
        return $this->render('achievement/index.html.twig', [
            'achievements' => $achievementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'achievement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $achievement = new Achievement();
        $form = $this->createForm(AchievementType::class, $achievement);
        dump($form);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($achievement);
            $entityManager->flush();

            return $this->redirectToRoute('achievement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('achievement/new.html.twig', [
            'achievement' => $achievement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'achievement_show', methods: ['GET'])]
    public function show(Achievement $achievement): Response
    {
        return $this->render('achievement/show.html.twig', [
            'achievement' => $achievement,
        ]);
    }

    #[Route('/{id}/edit', name: 'achievement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Achievement $achievement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AchievementType::class, $achievement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('achievement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('achievement/edit.html.twig', [
            'achievement' => $achievement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'achievement_delete', methods: ['POST'])]
    public function delete(Request $request, Achievement $achievement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$achievement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($achievement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('achievement_index', [], Response::HTTP_SEE_OTHER);
    }
}
