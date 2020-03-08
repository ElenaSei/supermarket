<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200308122230 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bill_item DROP FOREIGN KEY FK_EC044DB44584665A');
        $this->addSql('ALTER TABLE bill_item CHANGE product_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bill_item ADD CONSTRAINT FK_EC044DB44584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bill_item DROP FOREIGN KEY FK_EC044DB44584665A');
        $this->addSql('ALTER TABLE bill_item CHANGE product_id product_id INT NOT NULL');
        $this->addSql('ALTER TABLE bill_item ADD CONSTRAINT FK_EC044DB44584665A FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
