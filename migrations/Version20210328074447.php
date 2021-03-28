<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210328074447 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE food_allergens_restaurant (food_allergens_id INT NOT NULL, restaurant_id INT NOT NULL, INDEX IDX_5E072443D6B3B90 (food_allergens_id), INDEX IDX_5E072443B1E7706E (restaurant_id), PRIMARY KEY(food_allergens_id, restaurant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE food_allergens_restaurant ADD CONSTRAINT FK_5E072443D6B3B90 FOREIGN KEY (food_allergens_id) REFERENCES food_allergens (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_allergens_restaurant ADD CONSTRAINT FK_5E072443B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_allergens DROP FOREIGN KEY FK_FF0DF22FB1E7706E');
        $this->addSql('DROP INDEX IDX_FF0DF22FB1E7706E ON food_allergens');
        $this->addSql('ALTER TABLE food_allergens DROP restaurant_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE food_allergens_restaurant');
        $this->addSql('ALTER TABLE food_allergens ADD restaurant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE food_allergens ADD CONSTRAINT FK_FF0DF22FB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_FF0DF22FB1E7706E ON food_allergens (restaurant_id)');
    }
}
