<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210621185116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jeux ADD download_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE jeux ADD CONSTRAINT FK_3755B50D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_3755B50D12469DE2 ON jeux (category_id)');
        $this->addSql('ALTER TABLE user CHANGE name name VARCHAR(100) NOT NULL, CHANGE firstname firstname VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jeux DROP FOREIGN KEY FK_3755B50D12469DE2');
        $this->addSql('DROP INDEX IDX_3755B50D12469DE2 ON jeux');
        $this->addSql('ALTER TABLE jeux DROP download_url');
        $this->addSql('ALTER TABLE user CHANGE name name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE firstname firstname VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
