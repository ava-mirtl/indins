<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
        private EntityManagerInterface $entityManager;

        public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/books', methods: ['GET'])]
        public function getAllBooks(): JsonResponse
        {

        $books = $this->entityManager->getRepository(Book::class)->findAll();

        $booksData = [];
        foreach ($books as $book) {
            $authors = [];
            foreach ($book->getAuthors() as $author) {
                $authors[] = $author->getSurname();
            }
            $publisherName = '';
            if ($book->getPublisher() !== null) {
                $publisherName = $book->getPublisher()->getName();
            }

            $booksData[] = [
                'id' => $book->getId(),
                'name' => $book->getName(),
                'year' => $book->getYear(),
                'authors' => $authors,
                'publisher' => $publisherName,
            ];
        }

        return new JsonResponse($booksData);
    }
    #[Route('/books/new', methods: ['POST'])]
    public function createBook(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $book = new Book();
        $book->setName($requestData['name']);
        $book->setYear($requestData['year']);

        // Получаем автора по id
        $authorId = $requestData['author_id'];
        $author = $this->entityManager->getRepository(Author::class)->find($authorId);
        if (!$author) {
            return new JsonResponse(['error' => 'Author not found'], 404);
        }

        // Привязываем книгу к автору
        $book->addAuthor($author);

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Book created successfully'], 201);
    }
}
