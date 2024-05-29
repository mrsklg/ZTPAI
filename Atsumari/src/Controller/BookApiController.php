<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\ReadingSession;
use App\Entity\User;
use App\Entity\UserBookStats;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class BookApiController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('DELETE')) {
            return $this->removeBookFromCollection($request, $entityManager);
        } elseif ($request->isMethod('GET')) {
            return $this->getUsersBooks($request, $entityManager);
        }else {
            return new Response(null, Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    #[Route('/api/books', name: 'books', methods: ['GET'])]
    public function getUsersBooks(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userId = $this->getUser()->getId();
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);
        $books = $user->getBooks();

        $booksData = [];
        foreach ($books as $book) {
            $booksData[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'coverUrl' => $book->getCoverUrl(),
            ];
        }

        return new JsonResponse($booksData);
    }

    #[Route('/api/remove_book/{book_id}', name: 'remove_book', methods: ['DELETE'])]
    public function removeBookFromCollection(Request $request, EntityManagerInterface $entityManager): Response
    {

        $userId = $this->getUser()->getId();
        $bookId = $request->attributes->get('book_id');

        $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);
        $book = $entityManager->getRepository(Book::class)->findOneBy(['id' => $bookId]);

        if (!$user || !$book) {
            throw $this->createNotFoundException('User or book not found.');
        }

        $user->removeBook($book);

        $readingSessions = $entityManager->getRepository(ReadingSession::class)->findBy(['user_id' => $user, 'book_id' => $book]);
        foreach ($readingSessions as $readingSession) {
            $entityManager->remove($readingSession);
        }

        $userBookStats = $entityManager->getRepository(UserBookStats::class)->findBy(['user_id' => $user, 'book_id' => $book]);
        foreach ($userBookStats as $userBookStat) {
            $entityManager->remove($userBookStat);
        }

        $entityManager->flush();

        return new JsonResponse(['success' => 'Książka została pomyślnie usunięta z kolekcji'], 201);
    }

    #[Route('/api/books/is-read/{bookId}', name: 'is_book_read', methods: ['GET'])]
    public function isBookRead(int $bookId, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $userId = $this->getUser()->getId();
        $isRead = $entityManager->getRepository(UserBookStats::class)->isBookReadByUser($userId, $bookId);

        return $this->json(['is_read' => $isRead]);
    }
}
