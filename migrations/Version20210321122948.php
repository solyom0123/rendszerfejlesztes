<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210321122948 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE menu_restaurant');
        $this->addSql('ALTER TABLE menu ADD restaurant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_7D053A93B1E7706E ON menu (restaurant_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu_restaurant (menu_id INT NOT NULL, restaurant_id INT NOT NULL, INDEX IDX_CA38A6EDCCD7E912 (menu_id), INDEX IDX_CA38A6EDB1E7706E (restaurant_id), PRIMARY KEY(menu_id, restaurant_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE menu_restaurant ADD CONSTRAINT FK_CA38A6EDB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_restaurant ADD CONSTRAINT FK_CA38A6EDCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93B1E7706E');
        $this->addSql('DROP INDEX IDX_7D053A93B1E7706E ON menu');
        $this->addSql('ALTER TABLE menu DROP restaurant_id');
    }
}
