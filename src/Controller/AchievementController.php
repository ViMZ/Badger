<?php

namespace App\Controller;

use App\Entity\Achievement;
use App\Entity\Step;
use App\Entity\User;
use App\Form\AchievementType;
use App\Form\StepType;
use App\Repository\AchievementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/achievement')]
class AchievementController extends AbstractController
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('/', name: 'achievement_list', methods: ['GET'])]
    public function list(AchievementRepository $achievementRepository): Response
    {
        /** @var User */
        $user = $this->getUser();

        if (!$user->hasRole(User::ROLE_ADMIN)) {
            return $this->render('achievement/list.html.twig', [
                'achievements' => $achievementRepository->findByUser($user),
            ]);
        }

        return $this->render('achievement/list.html.twig', [
            'achievements' => $achievementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'achievement_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $step = new Step();
        $achievement = new Achievement();
        $achievement->addStep($step);
        $form = $this->createForm(AchievementType::class, $achievement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($achievement);
            $this->em->flush();
            $this->addFlash('success', 'achievement.new.success');

            return $this->redirectToRoute('achievement_list', [], Response::HTTP_SEE_OTHER);
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
    public function edit(Request $request, Achievement $achievement): Response
    {
        $form = $this->createForm(AchievementType::class, $achievement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('achievement_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('achievement/edit.html.twig', [
            'achievement' => $achievement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'achievement_delete', methods: ['POST'])]
    public function delete(Request $request, Achievement $achievement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$achievement->getId(), $request->request->get('_token'))) {
            $this->em->remove($achievement);
            $this->em->flush();
        }

        return $this->redirectToRoute('achievement_list', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/steps', name: 'achievement_steps', methods: ['GET'])]
    public function listSteps(Achievement $achievement): Response
    {
        $steps = $achievement->getSteps();

        return $this->render('step/list.html.twig', [
            'steps' => $steps,
            'achievement' => $achievement,
        ]);
    }

    #[Route('/{id}/step/new', name: 'achievement_steps_new', methods: ['GET', 'POST'])]
    public function addStep(Request $request, Achievement $achievement): Response
    {
        $step = new Step();
        $step->setAchievement($achievement);
        $form = $this->createForm(StepType::class, $step);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($step);
            $this->em->flush();
            $this->addFlash('success', 'step.new.success');

            return $this->redirectToRoute(
                'achievement_steps',
                ['id' => $achievement->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('step/new.html.twig', [
            'achievement' => $achievement,
            'step' => $step,
            'form' => $form,
        ]);
    }
}
