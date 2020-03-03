<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200303180221 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE promotion DROP INDEX IDX_C11D7DD14584665A, ADD UNIQUE INDEX UNIQ_C11D7DD14584665A (product_id)');
        $this->addSql('ALTER TABLE promotion CHANGE product_quantity quantity INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE promotion DROP INDEX UNIQ_C11D7DD14584665A, ADD INDEX IDX_C11D7DD14584665A (product_id)');
        $this->addSql('ALTER TABLE promotion CHANGE quantity product_quantity INT NOT NULL');
    }
}
