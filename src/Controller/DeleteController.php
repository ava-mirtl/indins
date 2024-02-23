<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Publisher;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends AbstractController
{ private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/{entity}/{id}/delete', methods: ['DELETE'])]
    public function deleteEntity(Request $request, $id, $entity): Response
    {
        // Проверяем, что метод запроса действительно DELETE
        if ($request->isMethod('DELETE')) {
            // Устанавливаем репозиторий сущности в зависимости от типа сущности
            switch ($entity) {
                case 'authors':
                    $repository = $this->entityManager->getRepository(Author::class);
                    break;
                case 'books':

                    $query = "DELETE FROM book_author WHERE book_id = :bookId";
                    $statement = $this->entityManager->getConnection()->prepare($query);
                    $statement->bindValue('bookId', $id);
                    $statement->execute();
                    $repository = $this->entityManager->getRepository(Book::class);
                    break;
                case 'publishers':
                    $repository = $this->entityManager->getRepository(Publisher::class);
                    break;
                default:
                    return new Response("Entity not found", Response::HTTP_NOT_FOUND);
            }

            // Находим сущность по ID
            $entityToDelete = $repository->find($id);

            // Проверяем, найдена ли сущность
            if (!$entityToDelete) {
                return new Response("Entity not found", Response::HTTP_NOT_FOUND);
            }

            // Удаляем сущность
            $this->entityManager->remove($entityToDelete);
            $this->entityManager->flush();

            return new Response("Entity with ID $id has been successfully deleted.", Response::HTTP_OK);
        }

        return new Response("Method Not Allowed", Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
