<?php

namespace App\Entity;

use App\Repository\AchievementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AchievementRepository::class)]
class Achievement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank()]
    private ?string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\NotBlank()]
    private ?string $description;

    #[ORM\OneToMany(mappedBy: 'achievement', targetEntity: UserAchievement::class, orphanRemoval: true)]
    private $userAchievements;

    #[ORM\OneToMany(mappedBy: 'achievement', targetEntity: Badge::class, orphanRemoval: true, cascade: ['persist'])]
    private $badges;

    public function __construct(string $name = '', string $description = '')
    {
        $this->userAchievements = new ArrayCollection();
        $this->name = $name;
        $this->description = $description;
        $this->badges = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|UserAchievement[]
     */
    public function getUserAchievements(): Collection
    {
        return $this->userAchievements;
    }

    public function addUserAchievement(UserAchievement $userAchievement): self
    {
        if (!$this->userAchievements->contains($userAchievement)) {
            $this->userAchievements[] = $userAchievement;
        }

        return $this;
    }

    public function removeUserAchievement(UserAchievement $userAchievement): self
    {
        $this->userAchievements->removeElement($userAchievement);

        return $this;
    }

    /**
     * @return Collection<int, Badge>
     */
    public function getBadges(): Collection
    {
        return $this->badges;
    }

    public function addBadge(Badge $badge): self
    {
        if (!$this->badges->contains($badge)) {
            $this->badges[] = $badge;
            $badge->setAchievement($this);
        }

        return $this;
    }

    public function removeBadge(Badge $badge): self
    {
        if ($this->badges->removeElement($badge)) {
            // set the owning side to null (unless already changed)
            if ($badge->getAchievement() === $this) {
                $badge->setAchievement(null);
            }
        }

        return $this;
    }
}
