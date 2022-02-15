<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends AppFixtures
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function loadData(ObjectManager $manager): void
    {
        $admin = new User();
        $admin
            ->setEmail('admin@email.com')
            ->setPassword($this->hasher->hashPassword($admin, '123456'))
            ->setName('Admin')
            ->setIsVerified(true)
            ->setRoles([User::ROLE_ADMIN]);

        $manager->persist($admin);

        $user = new User();
        $user
            ->setEmail('user@email.com')
            ->setPassword($this->hasher->hashPassword($user, '123456'))
            ->setName('User')
            ->setIsVerified(true)
            ->setRoles([User::ROLE_USER]);

        $manager->persist($user);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
