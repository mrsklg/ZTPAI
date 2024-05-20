<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\UserBookStatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SessionController extends AbstractController
{
    #[Route('/reading_session', name: 'reading_session')]
    #[isGranted('ROLE_USER')]
    public function readingSession(BookRepository $bookRepository, UserBookStatsRepository $bookStatsRepository): Response
    {
        $userId = $this->getUser()->getId();
        $currentBook = $bookRepository->getCurrentBookForUser($userId);
        if ($currentBook) {
            $bookId = $currentBook['id'];
        } else {
            $bookId = null;
        }

        $userBookStats = $bookStatsRepository->findOneBy(['user_id' => $userId, 'book_id' => $bookId]);
        return $this->render('session/reading_session.html.twig', [
            'pagesRead' => $userBookStats->getPagesReadCount(),
            'book' => $currentBook
        ]);
    }
}
