<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210321083641 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE food_restaurant');
        $this->addSql('ALTER TABLE food ADD restaurant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE food ADD CONSTRAINT FK_D43829F7B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_D43829F7B1E7706E ON food (restaurant_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE food_restaurant (food_id INT NOT NULL, restaurant_id INT NOT NULL, INDEX IDX_8CD0A29EBA8E87C4 (food_id), INDEX IDX_8CD0A29EB1E7706E (restaurant_id), PRIMARY KEY(food_id, restaurant_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE food_restaurant ADD CONSTRAINT FK_8CD0A29EB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_restaurant ADD CONSTRAINT FK_8CD0A29EBA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food DROP FOREIGN KEY FK_D43829F7B1E7706E');
        $this->addSql('DROP INDEX IDX_D43829F7B1E7706E ON food');
        $this->addSql('ALTER TABLE food DROP restaurant_id');
    }
}
