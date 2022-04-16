<?php

namespace App\Service;

use App\Entity\Achievement;
use App\Entity\Badge;
use App\Entity\User;
use App\Entity\UserAchievement;
use App\Exception\BadgePersistenceException;
use App\Repository\UserAchievementRepository;
use Doctrine\ORM\EntityManagerInterface;

class BadgeService
{
    private UserAchievementRepository $userAchievementRepository;
    private EntityManagerInterface $em;

    public function __construct(
        UserAchievementRepository $userAchievementRepository,
        EntityManagerInterface $em
    ) {
        $this->userAchievementRepository = $userAchievementRepository;
        $this->em = $em;
    }

    public function giveBadge(User $user, Achievement $achievement): void
    {
        // Vérifier si l'utilisateur a cet achievement
        $userAchievement = $this->userAchievementRepository->findOneBy([
            'user' => $user,
            'achievement' => $achievement,
        ]);

        if (!$userAchievement) {
            $userAchievement = new UserAchievement($user, $achievement);
        } else {
            $userAchievement->addStep();
        }

        try {
            $this->em->persist($userAchievement);
            $this->em->flush();
        } catch (\Throwable $th) {
            throw new BadgePersistenceException($th->getMessage(), $th->getCode());
        }
    }

    // public function getHigherBadge(User $user, Achievement $achievement): Badge
    // {
    //     // Récupérer
    // }
}
