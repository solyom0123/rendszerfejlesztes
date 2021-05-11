<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210510205458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE food_sale (food_id INT NOT NULL, sale_id INT NOT NULL, INDEX IDX_506C68D4BA8E87C4 (food_id), INDEX IDX_506C68D44A7E4868 (sale_id), PRIMARY KEY(food_id, sale_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_sale (menu_id INT NOT NULL, sale_id INT NOT NULL, INDEX IDX_2D04304BCCD7E912 (menu_id), INDEX IDX_2D04304B4A7E4868 (sale_id), PRIMARY KEY(menu_id, sale_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE food_sale ADD CONSTRAINT FK_506C68D4BA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_sale ADD CONSTRAINT FK_506C68D44A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_sale ADD CONSTRAINT FK_2D04304BCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_sale ADD CONSTRAINT FK_2D04304B4A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food DROP FOREIGN KEY FK_D43829F74A7E4868');
        $this->addSql('DROP INDEX IDX_D43829F74A7E4868 ON food');
        $this->addSql('ALTER TABLE food DROP sale_id');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A934A7E4868');
        $this->addSql('DROP INDEX IDX_7D053A934A7E4868 ON menu');
        $this->addSql('ALTER TABLE menu DROP sale_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE food_sale');
        $this->addSql('DROP TABLE menu_sale');
        $this->addSql('ALTER TABLE food ADD sale_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE food ADD CONSTRAINT FK_D43829F74A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id)');
        $this->addSql('CREATE INDEX IDX_D43829F74A7E4868 ON food (sale_id)');
        $this->addSql('ALTER TABLE menu ADD sale_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A934A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id)');
        $this->addSql('CREATE INDEX IDX_7D053A934A7E4868 ON menu (sale_id)');
        $this->addSql('ALTER TABLE order_food DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE order_menu DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE suborder_food DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE suborder_menu DROP PRIMARY KEY');
    }
}
