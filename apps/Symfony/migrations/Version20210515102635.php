<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210515102635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `product` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE product(
                id BINARY(16) NOT NULL,
                name VARCHAR (200) NOT NULL,
                price DECIMAL (5,2) NOT NULL,
                offer_price DECIMAL (5,2),
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
            ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE product');
    }
}
