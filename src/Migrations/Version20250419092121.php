<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250419092121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the request log table for Sylius Analytics Plugin';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE threebrs_request_log_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);

        $this->addSql(<<<'SQL'
            CREATE TABLE threebrs_request_log (
                id INT NOT NULL,
                customer_id INT DEFAULT NULL,
                url TEXT NOT NULL,
                route_name VARCHAR(255) DEFAULT NULL,
                channel VARCHAR(255) DEFAULT NULL,
                session_id VARCHAR(255) DEFAULT NULL,
                ip_address VARCHAR(45) DEFAULT NULL,
                user_agent TEXT DEFAULT NULL,
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                PRIMARY KEY(id)
            )
        SQL);

        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_customer_id ON threebrs_request_log (customer_id)
        SQL);

        $this->addSql(<<<'SQL'
            ALTER TABLE threebrs_request_log
            ADD CONSTRAINT FK_customer_id FOREIGN KEY (customer_id) REFERENCES sylius_customer (id) ON DELETE SET NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE threebrs_request_log');
        $this->addSql('DROP SEQUENCE threebrs_request_log_id_seq CASCADE');
    }
}
