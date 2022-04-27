<?php

namespace App\Entity;

use App\Repository\UserAchievementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserAchievementRepository::class)]
class UserAchievement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userAchievements')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Achievement::class, inversedBy: 'userAchievements')]
    #[ORM\JoinColumn(nullable: false)]
    private $achievement;

    #[ORM\Column(type: 'integer')]
    private $step = 1;

    public function __construct(User $user, Achievement $achievement)
    {
        $this->user = $user;
        $this->achievement = $achievement;
    }

    public function __toString()
    {
        return $this->achievement->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getAchievement(): ?Achievement
    {
        return $this->achievement;
    }

    public function getStep(): ?int
    {
        return $this->step;
    }

    public function setStep(int $step): self
    {
        $this->step = $step;

        return $this;
    }

    public function addStep(): self
    {
        ++$this->step;

        return $this;
    }

    public function getBadges(): array
    {
        return array_filter(
            $this->achievement->getBadges()->toArray(),
            function ($badge) {
                return $badge->getStep() <= $this->step;
            }
        );
    }

    public function getHigherBadge(): ?Badge
    {
        if ($this->getBadges() === []) {
            return null;
        }

        foreach ($this->getBadges() as $badge) {
            if (!isset($higherBadge)) {
                $higherBadge = $badge;

                continue;
            }

            if ($badge->getStep() > $higherBadge->getStep()) {
                $higherBadge = $badge;
            }
        }

        return $higherBadge;
    }
}
