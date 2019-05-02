<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190418155446 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE card_line DROP FOREIGN KEY FK_3D27B6542D1731E9');
        $this->addSql('ALTER TABLE ingredient_default DROP FOREIGN KEY FK_E2690B192D1731E9');
        $this->addSql('ALTER TABLE card_menu DROP FOREIGN KEY FK_91363831CDE0E2D4');
        $this->addSql('ALTER TABLE menu_default DROP FOREIGN KEY FK_6B851D61D99ECED0');
        $this->addSql('ALTER TABLE product_in_menu DROP FOREIGN KEY FK_86671E47CDE0E2D4');
        $this->addSql('ALTER TABLE card_menu DROP FOREIGN KEY FK_91363831C54C8C93');
        $this->addSql('ALTER TABLE menu_default DROP FOREIGN KEY FK_6B851D61C54C8C93');
        $this->addSql('ALTER TABLE menu_ingredient DROP FOREIGN KEY FK_4A02CCA2C54C8C93');
        $this->addSql('DROP TABLE card_line');
        $this->addSql('DROP TABLE card_menu');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE ingredient_default');
        $this->addSql('DROP TABLE menu_default');
        $this->addSql('DROP TABLE menu_ingredient');
        $this->addSql('DROP TABLE menu_type');
        $this->addSql('DROP TABLE product_in_menu');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE card_line (id INT AUTO_INCREMENT NOT NULL, id_card_id INT DEFAULT NULL, id_ingredient_id INT DEFAULT NULL, type INT NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_3D27B6542D1731E9 (id_ingredient_id), INDEX IDX_3D27B65494513350 (id_card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE card_menu (id INT AUTO_INCREMENT NOT NULL, card_id INT NOT NULL, ingredient_menu_id INT NOT NULL, type_id INT NOT NULL, INDEX IDX_91363831CDE0E2D4 (ingredient_menu_id), INDEX IDX_913638314ACC9A20 (card_id), INDEX IDX_91363831C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL COLLATE utf8_general_ci, price DOUBLE PRECISION NOT NULL, picture VARCHAR(100) DEFAULT NULL COLLATE utf8_general_ci, type INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ingredient_default (id INT AUTO_INCREMENT NOT NULL, id_ingredient_id INT DEFAULT NULL, product_id INT DEFAULT NULL, type INT NOT NULL, INDEX IDX_E2690B194584665A (product_id), INDEX IDX_E2690B192D1731E9 (id_ingredient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE menu_default (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, ingredientmenu_id INT NOT NULL, type_id INT NOT NULL, INDEX IDX_6B851D61D99ECED0 (ingredientmenu_id), INDEX IDX_6B851D614584665A (product_id), INDEX IDX_6B851D61C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE menu_ingredient (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, name VARCHAR(100) NOT NULL COLLATE utf8_general_ci, picture VARCHAR(100) DEFAULT NULL COLLATE utf8_general_ci, INDEX IDX_4A02CCA2C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE menu_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL COLLATE utf8_general_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_in_menu (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, ingredient_menu_id INT NOT NULL, INDEX IDX_86671E47CDE0E2D4 (ingredient_menu_id), INDEX IDX_86671E474584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE card_line ADD CONSTRAINT FK_3D27B6542D1731E9 FOREIGN KEY (id_ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE card_line ADD CONSTRAINT FK_3D27B65494513350 FOREIGN KEY (id_card_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE card_menu ADD CONSTRAINT FK_913638314ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE card_menu ADD CONSTRAINT FK_91363831C54C8C93 FOREIGN KEY (type_id) REFERENCES menu_type (id)');
        $this->addSql('ALTER TABLE card_menu ADD CONSTRAINT FK_91363831CDE0E2D4 FOREIGN KEY (ingredient_menu_id) REFERENCES menu_ingredient (id)');
        $this->addSql('ALTER TABLE ingredient_default ADD CONSTRAINT FK_E2690B192D1731E9 FOREIGN KEY (id_ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE ingredient_default ADD CONSTRAINT FK_E2690B194584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE menu_default ADD CONSTRAINT FK_6B851D614584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE menu_default ADD CONSTRAINT FK_6B851D61C54C8C93 FOREIGN KEY (type_id) REFERENCES menu_type (id)');
        $this->addSql('ALTER TABLE menu_default ADD CONSTRAINT FK_6B851D61D99ECED0 FOREIGN KEY (ingredientmenu_id) REFERENCES menu_ingredient (id)');
        $this->addSql('ALTER TABLE menu_ingredient ADD CONSTRAINT FK_4A02CCA2C54C8C93 FOREIGN KEY (type_id) REFERENCES menu_type (id)');
        $this->addSql('ALTER TABLE product_in_menu ADD CONSTRAINT FK_86671E474584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_in_menu ADD CONSTRAINT FK_86671E47CDE0E2D4 FOREIGN KEY (ingredient_menu_id) REFERENCES menu_ingredient (id)');
    }
}
