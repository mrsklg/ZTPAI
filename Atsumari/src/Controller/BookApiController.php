<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Genre;
use App\Entity\ReadingSession;
use App\Entity\User;
use App\Entity\UserBookStats;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookApiController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            return $this->addBookToDb($request, $entityManager);
        } elseif ($request->isMethod('DELETE')) {
            return $this->removeBookFromCollection($request, $entityManager);
        } else {
            return new Response(null, Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    #[Route('/api/add_book_to_db', name: 'add_book_to_db', methods: ['POST'])]
    public function addBookToDb(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title'], $data['num_of_pages'], $data['cover_url'], $data['authors'], $data['genres'], $data['user_id'])) {
            return new JsonResponse(['error' => 'Brak wymaganych danych'], 400);
        }

        $title = $data['title'];
        $numOfPages = $data['num_of_pages'];
        $coverUrl = $data['cover_url'];
        $authors = $data['authors'];
        $genres = $data['genres'];
        $user_id= $data['user_id'];

        try {
            $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $user_id]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Użytkownik nie istnieje'], 404);
        }

        $entityManager->getConnection()->beginTransaction();
        try {
            $book = $entityManager->getRepository(Book::class)->findOneBy(['title' => $title]);
            if (!$book) {
                $book = new Book();
                $book->setTitle($title);
                $book->setNumOfPages($numOfPages);
                $book->setCoverUrl($coverUrl);
                $entityManager->persist($book);
            }

            foreach ($authors as $author) {
                $firstName = $author['first_name'];
                $lastName = $author['last_name'];

                // Sprawdź, czy autor istnieje w bazie danych
                $authorObject = $entityManager->getRepository(Author::class)->findOneBy(['first_name' => $firstName, 'last_name' => $lastName]);
                if (!$authorObject) {
                    $authorObject = new Author();
                    $authorObject->setFirstName($firstName);
                    $authorObject->setLastName($lastName);
                    $entityManager->persist($authorObject);
                }

                $book->addAuthor($authorObject);
            }

            foreach ($genres as $genre) {
                $genreName = $genre['genre_name'];

                $genreObject = $entityManager->getRepository(Genre::class)->findOneBy(['genre_name' => $genreName]);
                if (!$genreObject) {
                    $genreObject = new Genre();
                    $genreObject->setGenreName($genreName);
                    $entityManager->persist($genreObject);
                }

                $book->addGenre($genreObject);
            }

            $user->addBook($book);
            $book->addUser($user);

            $entityManager->flush();

            $entityManager->getConnection()->commit();

            return new JsonResponse(['success' => 'Książka została pomyślnie dodana'], 201);
        } catch (\Exception $e) {
            $entityManager->getConnection()->rollBack();
            return new JsonResponse(['error' => $e->getMessage()], 501);
        }
    }

    #[Route('/api/remove_book/{book_id}/user/{user_id}', name: 'remove_book', methods: ['DELETE'])]
    public function removeBookFromCollection(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userId = $request->attributes->get('user_id');
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

        return new JsonResponse(['success' => 'Książka została pomyślnie usunięta z kolekcji'], 204);
    }
}
