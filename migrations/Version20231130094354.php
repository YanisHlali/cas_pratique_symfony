<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231130094354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE books CHANGE isbn isbn VARCHAR(255) NOT NULL, CHANGE date date VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A92F675F31B FOREIGN KEY (author_id) REFERENCES authors (id)');
        $this->addSql('ALTER TABLE books RENAME INDEX author_id TO IDX_4A1B2A92F675F31B');
        $this->addSql('ALTER TABLE books_categories DROP FOREIGN KEY books_categories_ibfk_2');
        $this->addSql('ALTER TABLE books_categories DROP FOREIGN KEY books_categories_ibfk_1');
        $this->addSql('ALTER TABLE books_categories ADD CONSTRAINT FK_16746F157DD8AC20 FOREIGN KEY (books_id) REFERENCES books (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE books_categories ADD CONSTRAINT FK_16746F15A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A92F675F31B');
        $this->addSql('ALTER TABLE books CHANGE isbn isbn VARCHAR(255) DEFAULT NULL, CHANGE date date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE books RENAME INDEX idx_4a1b2a92f675f31b TO author_id');
        $this->addSql('ALTER TABLE books_categories DROP FOREIGN KEY FK_16746F157DD8AC20');
        $this->addSql('ALTER TABLE books_categories DROP FOREIGN KEY FK_16746F15A21214B7');
        $this->addSql('ALTER TABLE books_categories ADD CONSTRAINT books_categories_ibfk_2 FOREIGN KEY (categories_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE books_categories ADD CONSTRAINT books_categories_ibfk_1 FOREIGN KEY (books_id) REFERENCES books (id)');
    }
}
