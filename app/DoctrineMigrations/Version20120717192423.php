<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120717192423 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

        $this->addSql("CREATE TABLE invitation (id INT AUTO_INCREMENT NOT NULL, organization_id INT NOT NULL, referrer_id INT NOT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, confirmed_at DATETIME DEFAULT NULL, INDEX IDX_F11D61A232C8A3DE (organization_id), INDEX IDX_F11D61A2798C22DB (referrer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE organization (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE recognition (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, recipient_id INT NOT NULL, points INT NOT NULL, reason LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_95DB6FB2F624B39D (sender_id), INDEX IDX_95DB6FB2E92F8F78 (recipient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, organization_id INT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT '(DC2Type:array)', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, points INT NOT NULL, UNIQUE INDEX UNIQ_1483A5E992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_1483A5E9A0D96FBF (email_canonical), INDEX IDX_1483A5E932C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A232C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)");
        $this->addSql("ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2798C22DB FOREIGN KEY (referrer_id) REFERENCES users (id)");
        $this->addSql("ALTER TABLE recognition ADD CONSTRAINT FK_95DB6FB2F624B39D FOREIGN KEY (sender_id) REFERENCES users (id)");
        $this->addSql("ALTER TABLE recognition ADD CONSTRAINT FK_95DB6FB2E92F8F78 FOREIGN KEY (recipient_id) REFERENCES users (id)");
        $this->addSql("ALTER TABLE users ADD CONSTRAINT FK_1483A5E932C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

        $this->addSql("ALTER TABLE invitation DROP FOREIGN KEY FK_F11D61A232C8A3DE");
        $this->addSql("ALTER TABLE users DROP FOREIGN KEY FK_1483A5E932C8A3DE");
        $this->addSql("ALTER TABLE invitation DROP FOREIGN KEY FK_F11D61A2798C22DB");
        $this->addSql("ALTER TABLE recognition DROP FOREIGN KEY FK_95DB6FB2F624B39D");
        $this->addSql("ALTER TABLE recognition DROP FOREIGN KEY FK_95DB6FB2E92F8F78");
        $this->addSql("DROP TABLE invitation");
        $this->addSql("DROP TABLE organization");
        $this->addSql("DROP TABLE recognition");
        $this->addSql("DROP TABLE users");
    }
}
