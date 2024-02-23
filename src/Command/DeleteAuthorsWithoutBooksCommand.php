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
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand(
    name: 'DeleteAuthorsWithoutBooksCommand',
    description: 'delete authors without books command',
)]
class DeleteAuthorsWithoutBooksCommand extends Command
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

        // Получение списка всех авторов
        $authors = $this->entityManager->getRepository(Author::class)->findAll();

        $authorsToDelete = [];

        foreach ($authors as $author) {
            if ($author->getBooks()->isEmpty()) {
                $authorsToDelete[] = $author;
            }
        }

        foreach ($authorsToDelete as $author) {
            $this->entityManager->remove($author);
        }

        $this->entityManager->flush();

        // Вывод сообщения об успешном сохранении
        $output->writeln('Авторы без книг успешно удалены из базы данных.');

        return Command::SUCCESS;
    }
}
