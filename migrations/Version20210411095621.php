<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210411095621 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, total INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_F52993989395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_food (order_id INT NOT NULL, food_id INT NOT NULL, INDEX IDX_99C913E08D9F6D38 (order_id), INDEX IDX_99C913E0BA8E87C4 (food_id), PRIMARY KEY(order_id, food_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suborder (id INT AUTO_INCREMENT NOT NULL, parent_order_id INT NOT NULL, courier_id INT DEFAULT NULL, restaurant_id INT NOT NULL, status VARCHAR(20) NOT NULL, total_price INT NOT NULL, INDEX IDX_8F9A7C421252C1E9 (parent_order_id), INDEX IDX_8F9A7C42E3D8151C (courier_id), INDEX IDX_8F9A7C42B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suborder_food (suborder_id INT NOT NULL, food_id INT NOT NULL, INDEX IDX_C658F443860FD0B1 (suborder_id), INDEX IDX_C658F443BA8E87C4 (food_id), PRIMARY KEY(suborder_id, food_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993989395C3F3 FOREIGN KEY (customer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_food ADD CONSTRAINT FK_99C913E08D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_food ADD CONSTRAINT FK_99C913E0BA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE suborder ADD CONSTRAINT FK_8F9A7C421252C1E9 FOREIGN KEY (parent_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE suborder ADD CONSTRAINT FK_8F9A7C42E3D8151C FOREIGN KEY (courier_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE suborder ADD CONSTRAINT FK_8F9A7C42B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE suborder_food ADD CONSTRAINT FK_C658F443860FD0B1 FOREIGN KEY (suborder_id) REFERENCES suborder (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE suborder_food ADD CONSTRAINT FK_C658F443BA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_food DROP FOREIGN KEY FK_99C913E08D9F6D38');
        $this->addSql('ALTER TABLE suborder DROP FOREIGN KEY FK_8F9A7C421252C1E9');
        $this->addSql('ALTER TABLE suborder_food DROP FOREIGN KEY FK_C658F443860FD0B1');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_food');
        $this->addSql('DROP TABLE suborder');
        $this->addSql('DROP TABLE suborder_food');
    }
}
