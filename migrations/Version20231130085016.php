<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231130085016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE books_categories (books_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_16746F157DD8AC20 (books_id), INDEX IDX_16746F15A21214B7 (categories_id), PRIMARY KEY(books_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE books_categories ADD CONSTRAINT FK_16746F157DD8AC20 FOREIGN KEY (books_id) REFERENCES books (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE books_categories ADD CONSTRAINT FK_16746F15A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE BookCategorie DROP FOREIGN KEY BookCategorie_ibfk_2');
        $this->addSql('ALTER TABLE BookCategorie DROP FOREIGN KEY BookCategorie_ibfk_1');
        $this->addSql('DROP TABLE BookCategorie');
        $this->addSql('ALTER TABLE Books CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE isbn isbn VARCHAR(255) NOT NULL, CHANGE date date VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE Books ADD CONSTRAINT FK_4A1B2A92F675F31B FOREIGN KEY (author_id) REFERENCES authors (id)');
        $this->addSql('ALTER TABLE Books RENAME INDEX author_id TO IDX_4A1B2A92F675F31B');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE BookCategorie (book_id INT NOT NULL, categorie_id INT NOT NULL, INDEX BookCategorie_ibfk_2 (categorie_id), INDEX IDX_4FCD4C5B16A2B381 (book_id), PRIMARY KEY(book_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE BookCategorie ADD CONSTRAINT BookCategorie_ibfk_2 FOREIGN KEY (categorie_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE BookCategorie ADD CONSTRAINT BookCategorie_ibfk_1 FOREIGN KEY (book_id) REFERENCES Books (id)');
        $this->addSql('ALTER TABLE books_categories DROP FOREIGN KEY FK_16746F157DD8AC20');
        $this->addSql('ALTER TABLE books_categories DROP FOREIGN KEY FK_16746F15A21214B7');
        $this->addSql('DROP TABLE books_categories');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A92F675F31B');
        $this->addSql('ALTER TABLE books CHANGE id id INT NOT NULL, CHANGE isbn isbn VARCHAR(255) DEFAULT NULL, CHANGE date date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE books RENAME INDEX idx_4a1b2a92f675f31b TO author_id');
    }
}
