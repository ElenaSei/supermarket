<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200308154235 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('INSERT INTO product (name, price) VALUES (\'A\', 50), (\'B\', 30), (\'C\', 20), (\'D\', 10)');
        $this->addSql('INSERT INTO promotion (product_id, quantity, price) VALUES ((SELECT product.id FROM product WHERE product.name = \'A\'), 3, 135), ((SELECT product.id FROM product WHERE product.name = \'B\'), 2, 45)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
