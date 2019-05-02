<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190415140512 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking_infos DROP lastname, DROP firstname, DROP address, DROP zipcode, DROP city, DROP mobile, DROP email');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking_infos ADD lastname VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD firstname VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD address VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD zipcode INT NOT NULL, ADD city VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD mobile VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
