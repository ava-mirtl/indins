<?php

namespace App\Controller;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/authors/new', methods: ['POST'])]
    public function createAuthor(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $author = new Author();
        $author->setName($requestData['name']);
        $author->setSurname($requestData['surname']);

        $this->entityManager->persist($author);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Author created successfully'], 201);
    }
}
