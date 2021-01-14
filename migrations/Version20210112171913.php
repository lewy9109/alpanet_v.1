<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210112171913 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer_domain (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name_domain VARCHAR(255) NOT NULL, INDEX IDX_166CC25AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pakiet (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, pakiet_start DATE NOT NULL, pakiet_end DATE NOT NULL, pakiet_time INT NOT NULL, rest_min INT DEFAULT NULL, pakiet_time_by_year INT DEFAULT NULL, settlement VARCHAR(255) NOT NULL, status_pakiet VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_FA7158F5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, company_name VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, date_add DATETIME NOT NULL, img VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_domain ADD CONSTRAINT FK_166CC25AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE pakiet ADD CONSTRAINT FK_FA7158F5A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_domain DROP FOREIGN KEY FK_166CC25AA76ED395');
        $this->addSql('ALTER TABLE pakiet DROP FOREIGN KEY FK_FA7158F5A76ED395');
        $this->addSql('DROP TABLE customer_domain');
        $this->addSql('DROP TABLE pakiet');
        $this->addSql('DROP TABLE users');
    }
}
