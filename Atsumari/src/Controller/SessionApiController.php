<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\ReadingSession;
use App\Entity\User;
use App\Entity\UserBookStats;
use App\Repository\BookRepository;
use App\Repository\ReadingSessionRepository;
use App\Repository\UserBookStatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class SessionApiController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $entityManager, BookRepository $bookRepository, ReadingSessionRepository $sessionRepository): Response
    {
        if ($request->isMethod('POST')) {
            return $this->addSession($request, $entityManager);
        } elseif ($request->isMethod('GET')) {
            return $this->getSessions($request, $entityManager, $bookRepository, $sessionRepository);
        }else {
            return new Response(null, Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    #[Route('/api/reading_session', name: 'reading_session_post', methods: ['POST'])]
    public function addSession(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $bookId = $request->query->get('bookId');

        $data = json_decode($request->getContent(), true);

        $book = $entityManager->getRepository(Book::class)->find($bookId);

        if (!$book) {
            return $this->json(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        $readingSession = new ReadingSession();
        $readingSession->setUserId($user);
        $readingSession->setBookId($book);

        if (isset($data['start_date'])) {
            $readingSession->setStartDate(new \DateTime($data['start_date']));
        } else {
            return $this->json(['error' => 'Start date is required'], Response::HTTP_BAD_REQUEST);
        }

        if (isset($data['end_date'])) {
            $readingSession->setEndDate(new \DateTime($data['end_date']));
        } else {
            return $this->json(['error' => 'End date is required'], Response::HTTP_BAD_REQUEST);
        }

        if (isset($data['pages_read'])) {
            if ($data['pages_read'] > $book->getNumOfPages()) {
                $readingSession->setPagesRead($book->getNumOfPages());
            } else {
                $readingSession->setPagesRead($data['pages_read']);
            }
        } else {
            return $this->json(['error' => 'Pages read is required'], Response::HTTP_BAD_REQUEST);
        }

        if (isset($data['duration'])) {
            $readingSession->setDuration($data['duration']);
        } else {
            return $this->json(['error' => 'Duration is required'], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($readingSession);
        $entityManager->flush();

        return new JsonResponse("session added successfully");
    }

    #[Route('/api/reading_sessions', name: 'reading_sessions', methods: ['GET'])]
    public function getSessions(Request $request, EntityManagerInterface $entityManager, BookRepository $bookRepository, ReadingSessionRepository $sessionRepository): Response
    {
        $userId = $this->getUser()->getId();
        $book = $bookRepository->getCurrentBookForUser($userId);
        $bookId = $book['id'];


        $readingSessions = $sessionRepository->findBy(['user_id' => $userId, 'book_id' => $bookId]);

        $sessionsData = [];
        foreach ($readingSessions as $session) {
            $sessionsData[] = [
                'startDate' => $session->getStartDate(),
                'endDate' => $session->getEndDate(),
                'duration' => $session->getDuration(),
                'pagesRead' => $session->getPagesRead()
            ];
        }
        return new JsonResponse($sessionsData);
    }
}