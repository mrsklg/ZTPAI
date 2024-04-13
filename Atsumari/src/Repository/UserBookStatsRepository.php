<?php

namespace App\Repository;

use App\Entity\UserBookStats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserBookStats>
 *
 * @method UserBookStats|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBookStats|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBookStats[]    findAll()
 * @method UserBookStats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBookStatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBookStats::class);
    }

//    /**
//     * @return UserBookStats[] Returns an array of UserBookStats objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserBookStats
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
