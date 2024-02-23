<?php

namespace App\Command;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Publisher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;


#[AsCommand(
    name: 'FillDatabaseCommand',
    description: 'Seeder for DB',
)]
class FillDatabaseCommand extends Command
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }


    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Массив данных для создания нескольких записей
        $data = [
            [
                'author_name' => 'Имя Автора 1',
                'author_surname' => 'Фамилия Автора 1',
                'book_name' => 'Название Книги 1',
                'book_year' => '2022',
                'publisher_name' => 'Название Издательства 1',
                'publisher_address' => 'Адрес Издательства 1',
            ],
            [
                'author_name' => 'Имя Автора 2',
                'author_surname' => 'Фамилия Автора 2',
                'book_name' => 'Название Книги 2',
                'book_year' => '2022',
                'publisher_name' => 'Название Издательства 2',
                'publisher_address' => 'Адрес Издательства 2',
            ],
            [
                'author_name' => 'Имя Автора 3',
                'author_surname' => 'Фамилия Автора 3',
                'book_name' => 'Название Книги 3',
                'book_year' => '2023',
                'publisher_name' => 'Название Издательства 3',
                'publisher_address' => 'Адрес Издательства 3',
            ],
            [
                'author_name' => 'Имя Автора 4',
                'author_surname' => 'Фамилия Автора 4',
                'book_name' => 'Название Книги 4',
                'book_year' => '2024',
                'publisher_name' => 'Название Издательства 4',
                'publisher_address' => 'Адрес Издательства 4',
            ],
        ];
        foreach ($data as $item) {
            // Создание автора
            $author = new Author();
            $author->setName($item['author_name']);
            $author->setSurname($item['author_surname']);

            // Создание книги
            $book = new Book();
            $book->setName($item['book_name']);
            $book->setYear($item['book_year']);
            // Создание издательства
            $publisher = new Publisher();
            $publisher->setName($item['publisher_name']);
            $publisher->setAddress($item['publisher_address']);

            // Связывание книги с автором
            $author->addBook($book);
            $book->addAuthor($author);

            // Связывание книги с издательством
            $book->setPublisher($publisher);

            // Сохранение в базу данных
            $this->entityManager->persist($author);
            $this->entityManager->persist($publisher);
            $this->entityManager->persist($book);
        }

        $this->entityManager->flush();

        // Вывод сообщения об успешном сохранении
        $output->writeln('Несколько авторов, книг и издательств успешно сохранены в базе данных.');

        return Command::SUCCESS;
    }
}
