<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CurrentBookController extends AbstractController
{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    #[Route('/api/current_book_data', name: 'current_book_data', methods: ['GET'])]
    public function getCurrentBookData(Request $request): JsonResponse
    {
        $userId = $this->getUser()->getId();

        if (!$userId) {
            return new JsonResponse(['error' => 'User not authenticated'], 401);
        }

        $currentBook = $this->bookRepository->getCurrentBookForUser($userId);

        if (!$currentBook) {
            return new JsonResponse(['error' => 'No current book found'], 404);
        }

        return $this->json($currentBook, 200, [], ['groups' => 'book:read']);
    }
}
