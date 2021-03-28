<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210328074332 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE courier_data (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, location VARCHAR(255) NOT NULL, mobile_number VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, vehicle_type VARCHAR(255) NOT NULL, from_working_date_monday TIME NOT NULL, to_working_date_monday TIME NOT NULL, from_working_date_tuesday TIME NOT NULL, to_working_date_tuesday TIME NOT NULL, from_working_date_wednesday TIME NOT NULL, to_working_date_wednesday TIME NOT NULL, from_working_date_thursday TIME NOT NULL, to_working_date_thursday TIME NOT NULL, from_working_date_friday TIME NOT NULL, to_working_date_friday TIME NOT NULL, from_working_date_saturday TIME NOT NULL, to_working_date_saturday TIME NOT NULL, from_working_date_sunday TIME NOT NULL, to_working_date_sunday TIME NOT NULL, UNIQUE INDEX UNIQ_4F34BF9BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE courier_data ADD CONSTRAINT FK_4F34BF9BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE courier_data');
    }
}
