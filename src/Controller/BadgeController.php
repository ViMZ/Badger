<?php

namespace App\Controller;

use App\Entity\Badge;
use App\Entity\User;
use App\Form\BadgeGiveType;
use App\Form\BadgeType;
use App\Service\BadgeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/badge')]
class BadgeController extends AbstractController
{
    private BadgeService $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    #[Route('/new', name: 'badge_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(User::ROLE_ADMIN);
        $badge = new Badge();
        $form = $this->createForm(BadgeType::class, $badge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($badge);
            $entityManager->flush();

            return $this->redirectToRoute('badge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('badge/new.html.twig', [
            'badge' => $badge,
            'form' => $form,
        ]);
    }

    #[Route('/give', name: 'badge_give', methods: ['GET', 'POST'])]
    public function give(Request $request): Response
    {
        $form = $this->createForm(BadgeGiveType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Badge attribué avec succès');
            $data = $form->getData();
            $this->badgeService->giveBadge($data['user'], $data['achievement']);

            return $this->redirectToRoute('achievement_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('badge/give.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'badge_show', methods: ['GET'])]
    public function show(Badge $badge): Response
    {
        return $this->render('badge/show.html.twig', [
            'badge' => $badge,
        ]);
    }

    #[Route('/{id}/edit', name: 'badge_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Badge $badge, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(User::ROLE_ADMIN);
        $form = $this->createForm(BadgeType::class, $badge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('badge_show', ['id' => $badge->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('badge/edit.html.twig', [
            'badge' => $badge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'badge_delete', methods: ['POST'])]
    public function delete(Request $request, Badge $badge, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(User::ROLE_ADMIN);
        if ($this->isCsrfTokenValid('delete'.$badge->getId(), $request->request->get('_token'))) {
            $entityManager->remove($badge);
            $entityManager->flush();
        }

        return $this->redirectToRoute('achievement_index', [], Response::HTTP_SEE_OTHER);
    }
}
