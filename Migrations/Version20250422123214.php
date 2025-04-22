<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250422123214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the threebrs_request_log table for tracking frontend visits in the Sylius analytics plugin.';
    }

    public function up(Schema $schema): void
    {
        // Create the request log table with all required fields and foreign keys
        $this->addSql(<<<'SQL'
            CREATE TABLE threebrs_request_log (
                id INT NOT NULL,
                channel_id INT NOT NULL,
                customer_id INT DEFAULT NULL,
                url TEXT NOT NULL,
                route_name VARCHAR(255) DEFAULT NULL,
                session_id VARCHAR(255) DEFAULT NULL,
                ip_address VARCHAR(45) DEFAULT NULL,
                user_agent TEXT DEFAULT NULL,
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                PRIMARY KEY(id)
            )
        SQL);

        // Indexes for performance on foreign keys
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_65C2764572F5A1AA ON threebrs_request_log (channel_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_65C276459395C3F3 ON threebrs_request_log (customer_id)
        SQL);

        // Foreign key to sylius_channel (NOT NULL)
        $this->addSql(<<<'SQL'
            ALTER TABLE threebrs_request_log
            ADD CONSTRAINT FK_65C2764572F5A1AA FOREIGN KEY (channel_id)
            REFERENCES sylius_channel (id)
            NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);

        // Foreign key to sylius_customer (nullable, ON DELETE SET NULL)
        $this->addSql(<<<'SQL'
            ALTER TABLE threebrs_request_log
            ADD CONSTRAINT FK_65C276459395C3F3 FOREIGN KEY (customer_id)
            REFERENCES sylius_customer (id)
            ON DELETE SET NULL
            NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // Drop foreign keys first
        $this->addSql(<<<'SQL'
            ALTER TABLE threebrs_request_log DROP CONSTRAINT FK_65C2764572F5A1AA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE threebrs_request_log DROP CONSTRAINT FK_65C276459395C3F3
        SQL);

        // Then drop the table
        $this->addSql(<<<'SQL'
            DROP TABLE threebrs_request_log
        SQL);
    }
}
