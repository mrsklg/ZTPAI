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

    public function findBooksReadPerYear(int $userId)
    {
        $qb = $this->createQueryBuilder('ubs')
            ->select('EXTRACT(YEAR FROM ubs.last_session_end) as year, COUNT(ubs.id) as book_count')
            ->innerJoin('ubs.book_id', 'b')
            ->where('ubs.user_id = :user')
            ->andWhere('ubs.pages_read_count >= b.num_of_pages')
            ->setParameter('user', $userId)
            ->groupBy('year')
            ->orderBy('year', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function findBooksReadLastYearPerMonth(int $userId)
    {
        $qb = $this->createQueryBuilder('ubs')
            ->select('EXTRACT(YEAR FROM ubs.last_session_end) as year, EXTRACT(MONTH FROM ubs.last_session_end) as month, COUNT(ubs.id) as book_count')
            ->innerJoin('ubs.book_id', 'b')
            ->where('ubs.user_id = :user')
            ->andWhere('ubs.pages_read_count >= b.num_of_pages')
            ->andWhere('ubs.last_session_end >= :lastYear')
            ->setParameter('user', $userId)
            ->setParameter('lastYear', (new \DateTime('-1 year'))->format('Y-m-d'))
            ->groupBy('year, month')
            ->orderBy('year', 'ASC')
            ->addOrderBy('month', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function findUserReadingStats(int $userId): array
    {
        $totalBooksQuery = $this->createQueryBuilder('ubs')
            ->select('COUNT(ubs.id) AS total_books')
            ->innerJoin('ubs.book_id', 'b')
            ->andWhere('ubs.user_id = :user')
            ->andWhere('ubs.pages_read_count >= b.num_of_pages')
            ->setParameter('user', $userId)
            ->getQuery()
            ->getSingleScalarResult();

        $avgReadingSpeedQuery = $this->createQueryBuilder('ubs')
            ->select('AVG(ubs.reading_speed) AS avg_reading_speed')
            ->andWhere('ubs.user_id = :user')
            ->setParameter('user', $userId)
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'total_books' => $totalBooksQuery,
            'avg_reading_speed' => $avgReadingSpeedQuery
        ];
    }

    public function isBookReadByUser(int $userId, int $bookId): bool
    {
        $qb = $this->createQueryBuilder('ubs')
            ->select('COUNT(ubs.id)')
            ->innerJoin('ubs.book_id', 'b')
            ->where('ubs.user_id = :user')
            ->andWhere('ubs.book_id = :book')
            ->andWhere('ubs.pages_read_count >= b.num_of_pages')
            ->setParameter('user', $userId)
            ->setParameter('book', $bookId);

        $count = $qb->getQuery()->getSingleScalarResult();

        return $count > 0;
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
