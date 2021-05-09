<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210502081706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sale (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, percent INT NOT NULL, INDEX IDX_E54BC005B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE food ADD sale_id INT NULL');
        $this->addSql('ALTER TABLE food ADD CONSTRAINT FK_D43829F74A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id)');
        $this->addSql('CREATE INDEX IDX_D43829F74A7E4868 ON food (sale_id)');
        $this->addSql('ALTER TABLE menu ADD sale_id INT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A934A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id)');
        $this->addSql('CREATE INDEX IDX_7D053A934A7E4868 ON menu (sale_id)');
        }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE food DROP FOREIGN KEY FK_D43829F74A7E4868');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A934A7E4868');
        $this->addSql('DROP TABLE sale');
        $this->addSql('DROP INDEX IDX_D43829F74A7E4868 ON food');
        $this->addSql('ALTER TABLE food DROP sale_id');
        $this->addSql('DROP INDEX IDX_7D053A934A7E4868 ON menu');
        $this->addSql('ALTER TABLE menu DROP sale_id');
    }
}
