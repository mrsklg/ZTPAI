<?php

namespace App\Controller;

use App\Repository\UserBookStatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Entity\ReadingSession;
use App\Entity\UserBookStats;

class ReadingSessionListener
{
    private $userBookStatsRepository;
    private $entityManager;

    public function __construct(UserBookStatsRepository $userBookStatsRepository, EntityManagerInterface $entityManager)
    {
        $this->userBookStatsRepository = $userBookStatsRepository;
        $this->entityManager = $entityManager;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof ReadingSession) {
            $userId = $entity->getUserId();
            $bookId = $entity->getBookId();

            $userBookStats = $this->userBookStatsRepository->findOneBy(['user_id' => $userId, 'book_id' => $bookId]);

            if (!$userBookStats) {
                $userBookStats = new UserBookStats();
                $userBookStats->setUserId($userId);
                $userBookStats->setBookId($bookId);
                $userBookStats->setTotalReadingTime($entity->getDuration());
                $userBookStats->setSessionsCount(1);
                $userBookStats->setPagesReadCount($entity->getPagesRead());
                $userBookStats->setLastSessionEnd($entity->getEndDate());
                $userBookStats->setReadingSpeed();

                $this->entityManager->persist($userBookStats);
            } else {
                $userBookStats->setTotalReadingTime($userBookStats->getTotalReadingTime() + $entity->getDuration());
                $userBookStats->setSessionsCount($userBookStats->getSessionsCount() + 1);
                $userBookStats->setPagesReadCount($userBookStats->getPagesReadCount() + $entity->getPagesRead());
                $userBookStats->setLastSessionEnd($entity->getEndDate());
                $userBookStats->setReadingSpeed();
            }

            $this->entityManager->flush();
        }
    }
}