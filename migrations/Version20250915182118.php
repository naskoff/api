<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250915182118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `users` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE users (
              id UUID NOT NULL,
              email VARCHAR(180) NOT NULL,
              roles JSON NOT NULL,
              password VARCHAR(255) NOT NULL,
              PRIMARY KEY(id)
            )
        SQL);

        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON users (email)');

        $this->addSql('COMMENT ON COLUMN users.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users');
    }
}
