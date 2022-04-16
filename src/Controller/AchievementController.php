<?php

namespace App\Controller;

use App\Entity\Achievement;
use App\Entity\User;
use App\Form\AchievementType;
use App\Repository\AchievementRepository;
use App\Repository\UserAchievementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/achievement')]
class AchievementController extends AbstractController
{
    public function __construct(
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
                'userAchievements' => $achievementRepository->findByUser($user),
                'allAchievements' => $achievementRepository->findAll(),
            ]);
        }

        return $this->render('admin/achievement/list.html.twig', [
            'achievements' => $achievementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'achievement_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $achievement = new Achievement();
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
    public function show(Achievement $achievement, UserAchievementRepository $userAchievementRepository): Response
    {
        $user = $this->getUser();
        $userAchievement = $userAchievementRepository->findOneBy(['achievement' => $achievement, 'user' => $user]);

        return $this->render('achievement/show.html.twig', [
            'achievement' => $achievement,
            'userAchievement' => $userAchievement,
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
        $this->em->remove($achievement);
        $this->em->flush();

        return $this->redirectToRoute('achievement_list', [], Response::HTTP_SEE_OTHER);
    }
}
