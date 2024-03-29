<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120719151752 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

        $this->addSql("ALTER TABLE message ADD organization_id INT NOT NULL");
        $this->addSql("ALTER TABLE message ADD CONSTRAINT FK_B6BD307F32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)");
        $this->addSql("CREATE INDEX IDX_B6BD307F32C8A3DE ON message (organization_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

        $this->addSql("ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F32C8A3DE");
        $this->addSql("DROP INDEX IDX_B6BD307F32C8A3DE ON message");
        $this->addSql("ALTER TABLE message DROP organization_id");
    }
}
