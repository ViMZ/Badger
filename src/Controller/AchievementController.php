<?php

namespace App\Controller;

use App\Entity\Achievement;
use App\Entity\User;
use App\Form\AchievementType;
use App\Repository\AchievementRepository;
use App\Repository\UserAchievementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/achievement')]
class AchievementController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private PaginatorInterface $paginator
    ) {
    }

    #[Route('/', name: 'achievement_list', methods: ['GET'])]
    public function list(AchievementRepository $achievementRepository, Request $request): Response
    {
        /** @var User */
        $user = $this->getUser();
        $query = $request->query->get('query');
        $page = $request->query->getInt('page', 1);

        if (!$user->hasRole(User::ROLE_ADMIN)) {
            return $this->render('achievement/list.html.twig', [
                'userAchievements' => $achievementRepository->findByUser($user),
                'allAchievements' => $achievementRepository->findAll(),
            ]);
        }

        if ($query !== null) {
            $pagination = $this->paginator->paginate(
                $achievementRepository->searchWith($query),
                $page,
                20
            );
        } else {
            $pagination = $this->paginator->paginate(
                $achievementRepository->findAllQuery(),
                $page,
                20
            );
        }

        return $this->render('admin/achievement/list.html.twig', [
            'achievements' => $pagination,
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

    #[Route('/{id}', name: 'achievement_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Achievement $achievement, UserAchievementRepository $userAchievementRepository): Response
    {
        $user = $this->getUser();
        $userAchievement = $userAchievementRepository->findOneBy(['achievement' => $achievement, 'user' => $user]);

        return $this->render('achievement/show.html.twig', [
            'achievement' => $achievement,
            'userAchievement' => $userAchievement,
        ]);
    }

    #[Route('/search/{query}', name: 'achievement_search', methods: ['GET'])]
    public function search(Request $request, AchievementRepository $achievementRepository, string $query = ''): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->paginator->paginate(
            $achievementRepository->searchWith($query),
            $page,
            20
        );

        return $this->render('admin/achievement/search_result.html.twig', [
            'achievements' => $pagination,
            'route' => 'achievement_list',
        ]);
    }

    #[Route('/{id}/edit', name: 'achievement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Achievement $achievement): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
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
