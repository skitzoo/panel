<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190418155842 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE customers_card');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE customers_card (id INT AUTO_INCREMENT NOT NULL, barcode VARCHAR(15) NOT NULL COLLATE utf8_general_ci, lastname VARCHAR(60) NOT NULL COLLATE utf8_general_ci, firstname VARCHAR(60) NOT NULL COLLATE utf8_general_ci, email VARCHAR(100) NOT NULL COLLATE utf8_general_ci, UNIQUE INDEX UNIQ_F56A58BE97AE0266 (barcode), UNIQUE INDEX UNIQ_F56A58BEE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
    }
}
