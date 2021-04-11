<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210411103351 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_food DROP FOREIGN KEY FK_99C913E08D9F6D38');
        $this->addSql('ALTER TABLE suborder DROP FOREIGN KEY FK_8F9A7C421252C1E9');
        $this->addSql('ALTER TABLE suborder_food DROP FOREIGN KEY FK_C658F443860FD0B1');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
