<?php

namespace App\Repository;

use App\Entity\Achievement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Achievement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Achievement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Achievement[]    findAll()
 * @method Achievement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AchievementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Achievement::class);
    }

    public function findByUser(UserInterface $user)
    {
        return $this->createQueryBuilder('a')
            ->join('a.userAchievements', 'ua')
            ->join('ua.user', 'u')
            ->andWhere('u = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
