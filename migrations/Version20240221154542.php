<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221154542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('author')) {
            $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, surname VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        }

        if (!$schema->hasTable('publisher')) {
            $this->addSql('CREATE TABLE publisher (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        }

        if (!$schema->hasTable('book')) {
            $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, year VARCHAR(5) NOT NULL, publisher_id INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_Book_Publisher FOREIGN KEY (publisher_id) REFERENCES publisher(id)');
        }

// Добавляем таблицу-ассоциацию между книгами и авторами
        if (!$schema->hasTable('book_author')) {
            $this->addSql('CREATE TABLE book_author (book_id INT NOT NULL, author_id INT NOT NULL, PRIMARY KEY(book_id, author_id))');
            $this->addSql('CREATE INDEX IDX_123456789 ON book_author (book_id)');
            $this->addSql('CREATE INDEX IDX_987654321 ON book_author (author_id)');
            $this->addSql('ALTER TABLE book_author ADD CONSTRAINT FK_123456789 FOREIGN KEY (book_id) REFERENCES book (id)');
            $this->addSql('ALTER TABLE book_author ADD CONSTRAINT FK_987654321 FOREIGN KEY (author_id) REFERENCES author (id)');
        }

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE publisher');
        $this->addSql('DROP TABLE book_author');

    }
}
