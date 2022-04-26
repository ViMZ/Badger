<?php

namespace App\DataFixtures;

use App\Entity\Achievement;
use Doctrine\Persistence\ObjectManager;

class AchievementFixture extends AppFixtures
{
    public function loadData(ObjectManager $manager): void
    {
        for ($i = 0; 65 > $i; ++$i) {
            $name = $this->faker->realText(15);
            $description = $this->faker->realText(100);
            $achievement = new Achievement($name, $description);
            $manager->persist($achievement);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
