<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210510215533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu_menu_category (menu_id INT NOT NULL, menu_category_id INT NOT NULL, INDEX IDX_8CDE09C1CCD7E912 (menu_id), INDEX IDX_8CDE09C17ABA83AE (menu_category_id), PRIMARY KEY(menu_id, menu_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu_menu_category ADD CONSTRAINT FK_8CDE09C1CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_menu_category ADD CONSTRAINT FK_8CDE09C17ABA83AE FOREIGN KEY (menu_category_id) REFERENCES menu_category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE menu_menu_category');
        $this->addSql('ALTER TABLE order_food DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE order_menu DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE suborder_food DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE suborder_menu DROP PRIMARY KEY');
    }
}
