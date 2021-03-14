<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210314082349 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE food (id INT AUTO_INCREMENT NOT NULL, from_date DATETIME DEFAULT NULL, to_date DATETIME DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE food_restaurant (food_id INT NOT NULL, restaurant_id INT NOT NULL, INDEX IDX_8CD0A29EBA8E87C4 (food_id), INDEX IDX_8CD0A29EB1E7706E (restaurant_id), PRIMARY KEY(food_id, restaurant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE food_allergens (id INT AUTO_INCREMENT NOT NULL, food_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_FF0DF22FBA8E87C4 (food_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE food_allergens_restaurant (food_allergens_id INT NOT NULL, restaurant_id INT NOT NULL, INDEX IDX_5E072443D6B3B90 (food_allergens_id), INDEX IDX_5E072443B1E7706E (restaurant_id), PRIMARY KEY(food_allergens_id, restaurant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE food_images (id INT AUTO_INCREMENT NOT NULL, food_id INT DEFAULT NULL, file_path VARCHAR(255) DEFAULT NULL, INDEX IDX_A031D4C9BA8E87C4 (food_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE food_images_restaurant (food_images_id INT NOT NULL, restaurant_id INT NOT NULL, INDEX IDX_97C7EC9C4CB5687E (food_images_id), INDEX IDX_97C7EC9CB1E7706E (restaurant_id), PRIMARY KEY(food_images_id, restaurant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_menu_category (menu_id INT NOT NULL, menu_category_id INT NOT NULL, INDEX IDX_8CDE09C1CCD7E912 (menu_id), INDEX IDX_8CDE09C17ABA83AE (menu_category_id), PRIMARY KEY(menu_id, menu_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_food (menu_id INT NOT NULL, food_id INT NOT NULL, INDEX IDX_1C77D9B9CCD7E912 (menu_id), INDEX IDX_1C77D9B9BA8E87C4 (food_id), PRIMARY KEY(menu_id, food_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_restaurant (menu_id INT NOT NULL, restaurant_id INT NOT NULL, INDEX IDX_CA38A6EDCCD7E912 (menu_id), INDEX IDX_CA38A6EDB1E7706E (restaurant_id), PRIMARY KEY(menu_id, restaurant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, address VARCHAR(255) DEFAULT NULL, waiting_time TIME DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, INDEX IDX_EB95123F7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_category (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_26E9D72EB1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_opening_time (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT DEFAULT NULL, day VARCHAR(255) DEFAULT NULL, open_time TIME DEFAULT NULL, close_time TIME DEFAULT NULL, INDEX IDX_7422DC2EB1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE food_restaurant ADD CONSTRAINT FK_8CD0A29EBA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_restaurant ADD CONSTRAINT FK_8CD0A29EB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_allergens ADD CONSTRAINT FK_FF0DF22FBA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id)');
        $this->addSql('ALTER TABLE food_allergens_restaurant ADD CONSTRAINT FK_5E072443D6B3B90 FOREIGN KEY (food_allergens_id) REFERENCES food_allergens (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_allergens_restaurant ADD CONSTRAINT FK_5E072443B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_images ADD CONSTRAINT FK_A031D4C9BA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id)');
        $this->addSql('ALTER TABLE food_images_restaurant ADD CONSTRAINT FK_97C7EC9C4CB5687E FOREIGN KEY (food_images_id) REFERENCES food_images (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_images_restaurant ADD CONSTRAINT FK_97C7EC9CB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_menu_category ADD CONSTRAINT FK_8CDE09C1CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_menu_category ADD CONSTRAINT FK_8CDE09C17ABA83AE FOREIGN KEY (menu_category_id) REFERENCES menu_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_food ADD CONSTRAINT FK_1C77D9B9CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_food ADD CONSTRAINT FK_1C77D9B9BA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_restaurant ADD CONSTRAINT FK_CA38A6EDCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_restaurant ADD CONSTRAINT FK_CA38A6EDB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE restaurant_category ADD CONSTRAINT FK_26E9D72EB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE restaurant_opening_time ADD CONSTRAINT FK_7422DC2EB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE food_restaurant DROP FOREIGN KEY FK_8CD0A29EBA8E87C4');
        $this->addSql('ALTER TABLE food_allergens DROP FOREIGN KEY FK_FF0DF22FBA8E87C4');
        $this->addSql('ALTER TABLE food_images DROP FOREIGN KEY FK_A031D4C9BA8E87C4');
        $this->addSql('ALTER TABLE menu_food DROP FOREIGN KEY FK_1C77D9B9BA8E87C4');
        $this->addSql('ALTER TABLE food_allergens_restaurant DROP FOREIGN KEY FK_5E072443D6B3B90');
        $this->addSql('ALTER TABLE food_images_restaurant DROP FOREIGN KEY FK_97C7EC9C4CB5687E');
        $this->addSql('ALTER TABLE menu_menu_category DROP FOREIGN KEY FK_8CDE09C1CCD7E912');
        $this->addSql('ALTER TABLE menu_food DROP FOREIGN KEY FK_1C77D9B9CCD7E912');
        $this->addSql('ALTER TABLE menu_restaurant DROP FOREIGN KEY FK_CA38A6EDCCD7E912');
        $this->addSql('ALTER TABLE menu_menu_category DROP FOREIGN KEY FK_8CDE09C17ABA83AE');
        $this->addSql('ALTER TABLE food_restaurant DROP FOREIGN KEY FK_8CD0A29EB1E7706E');
        $this->addSql('ALTER TABLE food_allergens_restaurant DROP FOREIGN KEY FK_5E072443B1E7706E');
        $this->addSql('ALTER TABLE food_images_restaurant DROP FOREIGN KEY FK_97C7EC9CB1E7706E');
        $this->addSql('ALTER TABLE menu_restaurant DROP FOREIGN KEY FK_CA38A6EDB1E7706E');
        $this->addSql('ALTER TABLE restaurant_category DROP FOREIGN KEY FK_26E9D72EB1E7706E');
        $this->addSql('ALTER TABLE restaurant_opening_time DROP FOREIGN KEY FK_7422DC2EB1E7706E');
        $this->addSql('DROP TABLE food');
        $this->addSql('DROP TABLE food_restaurant');
        $this->addSql('DROP TABLE food_allergens');
        $this->addSql('DROP TABLE food_allergens_restaurant');
        $this->addSql('DROP TABLE food_images');
        $this->addSql('DROP TABLE food_images_restaurant');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_menu_category');
        $this->addSql('DROP TABLE menu_food');
        $this->addSql('DROP TABLE menu_restaurant');
        $this->addSql('DROP TABLE menu_category');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE restaurant_category');
        $this->addSql('DROP TABLE restaurant_opening_time');
    }
}
