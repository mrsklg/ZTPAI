<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\UserBookStatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserBookStatsApiController extends AbstractController
{
    private UserBookStatsRepository $statsRepository;
    private BookRepository $bookRepository;

    public function __construct(UserBookStatsRepository $statsRepository, BookRepository $bookRepository)
    {
        $this->statsRepository = $statsRepository;
        $this->bookRepository = $bookRepository;
    }

    #[Route('/api/user_book_stats', name: 'user_book_stats', methods: ['GET'])]
    public function getCurrentBookData(): JsonResponse
    {
        $userId = $this->getUser()->getId();
        $currentBook = $this->bookRepository->getCurrentBookForUser($userId);
        $bookId = $currentBook["id"];

        $stats = $this->statsRepository->findOneBy(['user_id' => $userId, 'book_id' => $bookId]);

        return $this->json($stats);
    }
}
