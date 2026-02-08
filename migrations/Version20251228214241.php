<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251228214241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE loan ADD book_id INT NOT NULL, DROP yes, CHANGE borrwed_at borrowed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D0316A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('CREATE INDEX IDX_C5D30D0316A2B381 ON loan (book_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D0316A2B381');
        $this->addSql('DROP INDEX IDX_C5D30D0316A2B381 ON loan');
        $this->addSql('ALTER TABLE loan ADD yes VARCHAR(255) NOT NULL, DROP book_id, CHANGE borrowed_at borrwed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
