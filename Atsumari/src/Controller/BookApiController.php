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
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class BookApiController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $entityManager): Response
    {
//        if ($request->isMethod('POST')) {
//            return $this->addBookToDb($request, $entityManager);
//        } else
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

//    #[Route('/api/add_book_to_db', name: 'add_book_to_db', methods: ['POST'])]
//    public function addBookToDb(Request $request, EntityManagerInterface $entityManager): Response
//    {
//        $data = json_decode($request->getContent(), true);
//
//        if (!isset($data['user_id'])) {
//            $user_id = $this->getUser()->getId();
//        } else {
//            $user_id= $data['user_id'];
//        }
//
//        $title = $data['title'];
//        $numOfPages = $data['num_of_pages'];
//        $coverUrl = $data['cover_url'];
//        $authors = $data['authors'];
//        $genres = $data['genres'];
//
//        try {
//            $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $user_id]);
//        } catch (\Exception $e) {
//            return new JsonResponse(['error' => 'Użytkownik nie istnieje'], 404);
//        }
//
//        $entityManager->getConnection()->beginTransaction();
//        try {
//            $book = $entityManager->getRepository(Book::class)->findOneBy(['title' => $title]);
//            if (!$book) {
//                $book = new Book();
//                $book->setTitle($title);
//                $book->setNumOfPages($numOfPages);
//                $book->setCoverUrl($coverUrl);
//                $entityManager->persist($book);
//            }
//
//            foreach ($authors as $author) {
//                $firstName = $author['first_name'];
//                $lastName = $author['last_name'];
//
//                $authorObject = $entityManager->getRepository(Author::class)->findOneBy(['first_name' => $firstName, 'last_name' => $lastName]);
//                if (!$authorObject) {
//                    $authorObject = new Author();
//                    $authorObject->setFirstName($firstName);
//                    $authorObject->setLastName($lastName);
//                    $entityManager->persist($authorObject);
//                }
//
//                $book->addAuthor($authorObject);
//            }
//
//            foreach ($genres as $genre) {
//                $genreName = $genre['genre_name'];
//
//                $genreObject = $entityManager->getRepository(Genre::class)->findOneBy(['genre_name' => $genreName]);
//                if (!$genreObject) {
//                    $genreObject = new Genre();
//                    $genreObject->setGenreName($genreName);
//                    $entityManager->persist($genreObject);
//                }
//
//                $book->addGenre($genreObject);
//            }
//
//            $coverFile = $request->files->get('cover_file');
//            if ($coverFile) {
//                $coverFileName = uniqid().'.'.$coverFile->guessExtension();
//                $coverFile->move($this->getParameter('uploads_directory'), $coverFileName);
//                $book->setCoverUrl($coverFileName);
//            }
//
//            $user->addBook($book);
//            $book->addUser($user);
//
//            $entityManager->flush();
//
//            $entityManager->getConnection()->commit();
//
//            return new JsonResponse(['success' => 'Książka została pomyślnie dodana'], 201);
//        } catch (\Exception $e) {
//            $entityManager->getConnection()->rollBack();
//            return new JsonResponse(['error' => $e->getMessage()], 501);
//        }
//    }

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

    #[Route('/api/current_book', name: 'current_book', methods: ['GET'])]
    public function getCurrentBook(Request $request, EntityManagerInterface $entityManager): Response
    {
//        $userId = $this->getUser()->getId();
//        $userRepository = $entityManager->getRepository(User::class);
//        $user = $userRepository->find($userId);
//
//        // Assuming you have a way to get the current book of the user
//        // This part should be adjusted based on your actual data model
//        $currentBook = $user->getCurrentBook();
//
//        if (!$currentBook) {
//            return new JsonResponse(['error' => 'No current book found'], 404);
//        }
//
//        $bookData = [
//            'id' => $currentBook->getId(),
//            'title' => $currentBook->getTitle(),
//            'coverUrl' => $currentBook->getCoverUrl(),
//        ];
        $book = $entityManager->getRepository(Book::class)->findBy(['id' => 1]);

        return new JsonResponse($book);
    }
}
