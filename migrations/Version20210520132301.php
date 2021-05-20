<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210520132301 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE travailler_sur (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, dashboard_id INT DEFAULT NULL, INDEX IDX_AAB6864DA76ED395 (user_id), INDEX IDX_AAB6864DB9D04D2B (dashboard_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE travailler_sur ADD CONSTRAINT FK_AAB6864DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE travailler_sur ADD CONSTRAINT FK_AAB6864DB9D04D2B FOREIGN KEY (dashboard_id) REFERENCES dashboard (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE travailler_sur');
    }
}
