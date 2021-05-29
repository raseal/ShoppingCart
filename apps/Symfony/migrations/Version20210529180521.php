<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210529180521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `cart_lines` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE cart_lines(
                id BINARY(16) NOT NULL,
                id_cart BINARY(16) NOT NULL,
                id_product BINARY(16) NOT NULL,
                quantity SMALLINT NOT NULL,
                total DECIMAL (5,2) NOT NULL,
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
            ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE cart_lines');
    }


}
