<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250419092121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE threebrs_visit_log_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE threebrs_visit_log (id INT NOT NULL, url VARCHAR(2048) NOT NULL, route VARCHAR(255) NOT NULL, channel VARCHAR(255) DEFAULT NULL, customer VARCHAR(255) DEFAULT NULL, sessionId VARCHAR(255) DEFAULT NULL, ip VARCHAR(45) DEFAULT NULL, userAgent VARCHAR(255) DEFAULT NULL, visitedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP SEQUENCE threebrs_visit_log_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE threebrs_visit_log
        SQL);
    }
}
