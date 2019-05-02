<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190418163033 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE booking_infos_orders');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE booking_infos_orders (booking_infos_id INT NOT NULL, orders_id INT NOT NULL, INDEX IDX_D29FA5256F7ED247 (booking_infos_id), INDEX IDX_D29FA525CFFE9AD6 (orders_id), PRIMARY KEY(booking_infos_id, orders_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE booking_infos_orders ADD CONSTRAINT FK_D29FA5256F7ED247 FOREIGN KEY (booking_infos_id) REFERENCES booking_infos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE booking_infos_orders ADD CONSTRAINT FK_D29FA525CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id) ON DELETE CASCADE');
    }
}
