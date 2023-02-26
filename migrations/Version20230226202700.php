<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230226202700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__notification AS SELECT id, user_id, channel, payload, status, status_message, provider, provider_response FROM notification');
        $this->addSql('DROP TABLE notification');
        $this->addSql('CREATE TABLE notification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, channel VARCHAR(20) NOT NULL, payload CLOB NOT NULL --(DC2Type:json)
        , status VARCHAR(255) NOT NULL, status_message VARCHAR(255) DEFAULT NULL, provider VARCHAR(255) DEFAULT NULL, provider_response CLOB DEFAULT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO notification (id, user_id, channel, payload, status, status_message, provider, provider_response) SELECT id, user_id, channel, payload, status, status_message, provider, provider_response FROM __temp__notification');
        $this->addSql('DROP TABLE __temp__notification');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__notification AS SELECT id, user_id, channel, payload, status, status_message, provider, provider_response FROM notification');
        $this->addSql('DROP TABLE notification');
        $this->addSql('CREATE TABLE notification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, channel VARCHAR(20) NOT NULL, payload CLOB NOT NULL --(DC2Type:json)
        , status VARCHAR(255) NOT NULL, status_message VARCHAR(255) DEFAULT NULL, provider VARCHAR(255) DEFAULT NULL, provider_response VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO notification (id, user_id, channel, payload, status, status_message, provider, provider_response) SELECT id, user_id, channel, payload, status, status_message, provider, provider_response FROM __temp__notification');
        $this->addSql('DROP TABLE __temp__notification');
    }
}
