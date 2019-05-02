<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190415015142 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(64) NOT NULL, latsname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, zipcode INT NOT NULL, city VARCHAR(255) NOT NULL, mobile VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP INDEX UNIQ_8D93D6493C7323E0 ON user');
        $this->addSql('ALTER TABLE user ADD username VARCHAR(25) NOT NULL, ADD is_blocked TINYINT(1) NOT NULL, ADD password_requested_at DATETIME DEFAULT NULL, ADD token VARCHAR(255) DEFAULT NULL, ADD created_on DATETIME DEFAULT NULL, DROP lastname, DROP firstname, DROP address, DROP zipcode, DROP city, DROP mobile');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
        $this->addSql('ALTER TABLE user ADD lastname VARCHAR(255) NOT NULL COLLATE utf8_general_ci, ADD firstname VARCHAR(255) NOT NULL COLLATE utf8_general_ci, ADD address VARCHAR(255) NOT NULL COLLATE utf8_general_ci, ADD zipcode INT NOT NULL, ADD city VARCHAR(255) NOT NULL COLLATE utf8_general_ci, ADD mobile VARCHAR(10) NOT NULL COLLATE utf8_general_ci, DROP username, DROP is_blocked, DROP password_requested_at, DROP token, DROP created_on');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6493C7323E0 ON user (mobile)');
    }
}
