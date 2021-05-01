<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210418074556 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE food_images_restaurant');
        $this->addSql('ALTER TABLE food_images ADD restaurant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE food_images ADD CONSTRAINT FK_A031D4C9B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_A031D4C9B1E7706E ON food_images (restaurant_id)');
        $this->addSql('ALTER TABLE order_food ADD CONSTRAINT FK_99C913E08D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE suborder ADD CONSTRAINT FK_8F9A7C421252C1E9 FOREIGN KEY (parent_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE suborder_food ADD CONSTRAINT FK_C658F443860FD0B1 FOREIGN KEY (suborder_id) REFERENCES suborder (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE food_images_restaurant (food_images_id INT NOT NULL, restaurant_id INT NOT NULL, INDEX IDX_97C7EC9C4CB5687E (food_images_id), INDEX IDX_97C7EC9CB1E7706E (restaurant_id), PRIMARY KEY(food_images_id, restaurant_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE food_images_restaurant ADD CONSTRAINT FK_97C7EC9CB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_images_restaurant ADD CONSTRAINT FK_97C7EC9C4CB5687E FOREIGN KEY (food_images_id) REFERENCES food_images (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_images DROP FOREIGN KEY FK_A031D4C9B1E7706E');
        $this->addSql('DROP INDEX IDX_A031D4C9B1E7706E ON food_images');
        $this->addSql('ALTER TABLE food_images DROP restaurant_id');
        $this->addSql('ALTER TABLE order_food DROP FOREIGN KEY FK_99C913E08D9F6D38');
        $this->addSql('ALTER TABLE suborder DROP FOREIGN KEY FK_8F9A7C421252C1E9');
        $this->addSql('ALTER TABLE suborder_food DROP FOREIGN KEY FK_C658F443860FD0B1');
    }
}
