<?php

namespace App\Controller;

use App\Dto\Table\Table;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('/', name: 'user_list', methods: ['GET'])]
    public function list(): Response
    {
        $this->em->getFilters()->enable('noadmin');
        $users = $this->userRepository->findAllByBadge();

        $table = (new Table($users))
            ->add('Nom', function ($user) {
                return $user->getName();
            })
            ->add('Progressions', function ($user) {
                return \count($user->getUserAchievements());
            })
            ->add('Badges', function ($user) {
                $badgesNbr = 0;
                foreach ($user->getUserAchievements() as $userAchievements) {
                    $badgesNbr = $badgesNbr + \count($userAchievements->getAchievement()->getBadges());
                }

                return $badgesNbr;
            });

        return $this->render('user/list.html.twig', [
            'users' => $users,
            'table' => $table,
        ]);
    }

    #[Route('/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->json($user);
    }
}
