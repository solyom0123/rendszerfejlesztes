<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210509135050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_menu (order_id INT NOT NULL, menu_id INT NOT NULL, INDEX IDX_30F400848D9F6D38 (order_id), INDEX IDX_30F40084CCD7E912 (menu_id), PRIMARY KEY(order_id, menu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suborder_menu (suborder_id INT NOT NULL, menu_id INT NOT NULL, INDEX IDX_6F65E727860FD0B1 (suborder_id), INDEX IDX_6F65E727CCD7E912 (menu_id), PRIMARY KEY(suborder_id, menu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_menu ADD CONSTRAINT FK_30F400848D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_menu ADD CONSTRAINT FK_30F40084CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE suborder_menu ADD CONSTRAINT FK_6F65E727860FD0B1 FOREIGN KEY (suborder_id) REFERENCES suborder (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE suborder_menu ADD CONSTRAINT FK_6F65E727CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_menu DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE suborder_menu DROP PRIMARY KEY');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE order_menu');
        $this->addSql('DROP TABLE suborder_menu');
        $this->addSql('ALTER TABLE order_food DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE suborder_food DROP PRIMARY KEY');
    }
}
