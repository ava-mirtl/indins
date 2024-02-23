<?php

namespace App\Controller;

use App\Entity\Publisher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublisherController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/publishers/{id}', methods: ['PUT'])]
    public function editPublisher(Request $request, $id): Response
    {
        // Проверяем метод запроса
        if ($request->isMethod('PUT')) {
            $publisher = $this->entityManager->getRepository(Publisher::class)->find($id);

            if (!$publisher) {
                return new Response("Publisher not found", Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);

            if (isset($data['name'])) {
                $publisher->setName($data['name']);
            }

            if (isset($data['address'])) {
                $publisher->setAddress($data['address']);
            }

            $this->entityManager->flush();

            return new Response("Publisher with ID $id has been successfully updated.", Response::HTTP_OK);
        }

        return new Response("Method Not Allowed", Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
