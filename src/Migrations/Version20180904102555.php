<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20180904102555 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE admin ADD roles JSON NOT NULL');
        $this->addSql('COMMENT ON COLUMN admin.roles IS \'(DC2Type:json_array)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE admin DROP roles');
    }
}
