<?php

namespace App\Repository;

use App\Entity\ReadingSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReadingSession>
 *
 * @method ReadingSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReadingSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReadingSession[]    findAll()
 * @method ReadingSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReadingSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReadingSession::class);
    }

    //    /**
    //     * @return ReadingSession[] Returns an array of ReadingSession objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ReadingSession
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
