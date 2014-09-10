<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120905074507 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql(
            'UPDATE users SET enabled = ?',
            array(Type::getType('boolean')->convertToDatabaseValue(true, $this->connection->getDatabasePlatform()))
        );
    }

    public function down(Schema $schema)
    {
    }
}
