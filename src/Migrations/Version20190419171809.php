<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190419171809 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking_infos DROP FOREIGN KEY FK_7FB67FD84ACC9A20');
        $this->addSql('DROP INDEX UNIQ_7FB67FD84ACC9A20 ON booking_infos');
        $this->addSql('ALTER TABLE booking_infos DROP card_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking_infos ADD card_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE booking_infos ADD CONSTRAINT FK_7FB67FD84ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7FB67FD84ACC9A20 ON booking_infos (card_id)');
    }
}
