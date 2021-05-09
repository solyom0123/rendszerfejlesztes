<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210509091323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE food CHANGE sale_id sale_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu CHANGE sale_id sale_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE suborder ADD user_order_rating INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE food CHANGE sale_id sale_id INT NOT NULL');
        $this->addSql('ALTER TABLE menu CHANGE sale_id sale_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_food DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE suborder DROP user_order_rating');
    }
}
