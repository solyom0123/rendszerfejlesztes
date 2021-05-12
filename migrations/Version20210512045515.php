<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210512045515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` CHANGE customer_id customer_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` CHANGE customer_id customer_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_food DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE order_menu DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE suborder_food DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE suborder_menu DROP PRIMARY KEY');
    }
}
