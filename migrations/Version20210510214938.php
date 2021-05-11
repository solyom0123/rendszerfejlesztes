<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210510214938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sale_menu (sale_id INT NOT NULL, menu_id INT NOT NULL, INDEX IDX_C14B4E764A7E4868 (sale_id), INDEX IDX_C14B4E76CCD7E912 (menu_id), PRIMARY KEY(sale_id, menu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sale_food (sale_id INT NOT NULL, food_id INT NOT NULL, INDEX IDX_68765D124A7E4868 (sale_id), INDEX IDX_68765D12BA8E87C4 (food_id), PRIMARY KEY(sale_id, food_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sale_menu ADD CONSTRAINT FK_C14B4E764A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sale_menu ADD CONSTRAINT FK_C14B4E76CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sale_food ADD CONSTRAINT FK_68765D124A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sale_food ADD CONSTRAINT FK_68765D12BA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE food_sale');
        $this->addSql('DROP TABLE menu_menu_category');
        $this->addSql('DROP TABLE menu_sale');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE food_sale (food_id INT NOT NULL, sale_id INT NOT NULL, INDEX IDX_506C68D4BA8E87C4 (food_id), INDEX IDX_506C68D44A7E4868 (sale_id), PRIMARY KEY(food_id, sale_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE menu_menu_category (menu_id INT NOT NULL, menu_category_id INT NOT NULL, INDEX IDX_8CDE09C1CCD7E912 (menu_id), INDEX IDX_8CDE09C17ABA83AE (menu_category_id), PRIMARY KEY(menu_id, menu_category_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE menu_sale (menu_id INT NOT NULL, sale_id INT NOT NULL, INDEX IDX_2D04304BCCD7E912 (menu_id), INDEX IDX_2D04304B4A7E4868 (sale_id), PRIMARY KEY(menu_id, sale_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE food_sale ADD CONSTRAINT FK_506C68D44A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_sale ADD CONSTRAINT FK_506C68D4BA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_menu_category ADD CONSTRAINT FK_8CDE09C17ABA83AE FOREIGN KEY (menu_category_id) REFERENCES menu_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_menu_category ADD CONSTRAINT FK_8CDE09C1CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_sale ADD CONSTRAINT FK_2D04304B4A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_sale ADD CONSTRAINT FK_2D04304BCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE sale_menu');
        $this->addSql('DROP TABLE sale_food');
        $this->addSql('ALTER TABLE order_food DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE order_menu DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE suborder_food DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE suborder_menu DROP PRIMARY KEY');
    }
}
