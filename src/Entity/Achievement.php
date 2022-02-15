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

    #[ORM\OneToMany(mappedBy: 'achievement', targetEntity: Step::class, orphanRemoval: true, cascade: ['persist'])]
    private $steps;

    #[ORM\OneToMany(mappedBy: 'achievement', targetEntity: UserAchievement::class, orphanRemoval: true)]
    private $userAchievements;

    public function __construct(string $name = '', string $description = '')
    {
        $this->steps = new ArrayCollection();
        $this->userAchievements = new ArrayCollection();
        $this->name = $name;
        $this->description = $description;
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
     * @return Collection|Step[]
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Step $step): self
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->setAchievement($this);
        }

        return $this;
    }

    public function removeStep(Step $step): self
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getAchievement() === $this) {
                $step->setAchievement(null);
            }
        }

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
            $userAchievement->setAchievement($this);
        }

        return $this;
    }

    public function removeUserAchievement(UserAchievement $userAchievement): self
    {
        if ($this->userAchievements->removeElement($userAchievement)) {
            // set the owning side to null (unless already changed)
            if ($userAchievement->getAchievement() === $this) {
                $userAchievement->setAchievement(null);
            }
        }

        return $this;
    }
}
