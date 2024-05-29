<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function getCurrentBookForUser(int $userId): ?array
    {
//        return $this->createQueryBuilder('b')
//            ->select('b.id', 'c.id', 'b.title', 'b.cover_url', 'b.num_of_pages', 'rs.pages_read_count AS totalPagesRead')
//            ->leftJoin('b.users', 'u')
//            ->leftJoin('u.books', 'c')
//            ->leftJoin('c.userBookStats', 'rs', 'WITH', 'u.id = rs.user_id AND c.id = rs.book_id')
//            ->andWhere('u.id = :userId')
//            ->groupBy('b.id', 'c.id', 'b.title', 'b.num_of_pages')
//            ->having('rs.pages_read_count < b.num_of_pages')
//            ->setParameter('userId', $userId)
//            ->getQuery()
//            ->setMaxResults(1)
//            ->getOneOrNullResult();

//        $qb = $this->createQueryBuilder('b')
//            ->innerJoin('App\Entity\UserBookStats', 'ubs', Join::WITH, 'b.id = ubs.book_id')
//            ->where('ubs.user_id = :user')
//            ->andWhere('ubs.pages_read_count < b.num_of_pages')
//            ->setParameter('user', $userId)
//            ->setMaxResults(1);
//
//        return $qb->getQuery()->getOneOrNullResult();

        $qb = $this->createQueryBuilder('b')
            ->select('b.id', 'b.title', 'b.cover_url', 'b.num_of_pages', 'ubs.pages_read_count')
            ->innerJoin('App\Entity\UserBookStats', 'ubs', Join::WITH, 'b.id = ubs.book_id')
            ->where('ubs.user_id = :user')
            ->andWhere('ubs.pages_read_count < b.num_of_pages')
            ->setParameter('user', $userId)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
