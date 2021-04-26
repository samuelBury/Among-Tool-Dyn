<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210426080642 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carre (id INT AUTO_INCREMENT NOT NULL, ligne_id INT DEFAULT NULL, colonne_id INT DEFAULT NULL, valeur VARCHAR(255) NOT NULL, INDEX IDX_EC8E587B5A438E76 (ligne_id), INDEX IDX_EC8E587B213EAC9D (colonne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE colonne (id INT AUTO_INCREMENT NOT NULL, dashboard_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, rank INT NOT NULL, INDEX IDX_65F87C44B9D04D2B (dashboard_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dashboard (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, ligne_id INT DEFAULT NULL, url_doc VARCHAR(255) NOT NULL, tmp_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D8698A765A438E76 (ligne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE droit (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gerer (id INT AUTO_INCREMENT NOT NULL, droit_id INT DEFAULT NULL, user_id INT DEFAULT NULL, colonne_id INT DEFAULT NULL, INDEX IDX_103C68BD5AA93370 (droit_id), INDEX IDX_103C68BDA76ED395 (user_id), INDEX IDX_103C68BD213EAC9D (colonne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne (id INT AUTO_INCREMENT NOT NULL, dashboard_id INT DEFAULT NULL, INDEX IDX_57F0DB83B9D04D2B (dashboard_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE carre ADD CONSTRAINT FK_EC8E587B5A438E76 FOREIGN KEY (ligne_id) REFERENCES ligne (id)');
        $this->addSql('ALTER TABLE carre ADD CONSTRAINT FK_EC8E587B213EAC9D FOREIGN KEY (colonne_id) REFERENCES colonne (id)');
        $this->addSql('ALTER TABLE colonne ADD CONSTRAINT FK_65F87C44B9D04D2B FOREIGN KEY (dashboard_id) REFERENCES dashboard (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A765A438E76 FOREIGN KEY (ligne_id) REFERENCES ligne (id)');
        $this->addSql('ALTER TABLE gerer ADD CONSTRAINT FK_103C68BD5AA93370 FOREIGN KEY (droit_id) REFERENCES droit (id)');
        $this->addSql('ALTER TABLE gerer ADD CONSTRAINT FK_103C68BDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE gerer ADD CONSTRAINT FK_103C68BD213EAC9D FOREIGN KEY (colonne_id) REFERENCES colonne (id)');
        $this->addSql('ALTER TABLE ligne ADD CONSTRAINT FK_57F0DB83B9D04D2B FOREIGN KEY (dashboard_id) REFERENCES dashboard (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carre DROP FOREIGN KEY FK_EC8E587B213EAC9D');
        $this->addSql('ALTER TABLE gerer DROP FOREIGN KEY FK_103C68BD213EAC9D');
        $this->addSql('ALTER TABLE colonne DROP FOREIGN KEY FK_65F87C44B9D04D2B');
        $this->addSql('ALTER TABLE ligne DROP FOREIGN KEY FK_57F0DB83B9D04D2B');
        $this->addSql('ALTER TABLE gerer DROP FOREIGN KEY FK_103C68BD5AA93370');
        $this->addSql('ALTER TABLE carre DROP FOREIGN KEY FK_EC8E587B5A438E76');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A765A438E76');
        $this->addSql('ALTER TABLE gerer DROP FOREIGN KEY FK_103C68BDA76ED395');
        $this->addSql('DROP TABLE carre');
        $this->addSql('DROP TABLE colonne');
        $this->addSql('DROP TABLE dashboard');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE droit');
        $this->addSql('DROP TABLE gerer');
        $this->addSql('DROP TABLE ligne');
        $this->addSql('DROP TABLE user');
    }
}
