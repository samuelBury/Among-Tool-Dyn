<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210520124343 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE posseder_droit_dash DROP FOREIGN KEY FK_27B7D34AB9D04D2B');
        $this->addSql('DROP INDEX IDX_27B7D34AB9D04D2B ON posseder_droit_dash');
        $this->addSql('ALTER TABLE posseder_droit_dash DROP dashboard_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE posseder_droit_dash ADD dashboard_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE posseder_droit_dash ADD CONSTRAINT FK_27B7D34AB9D04D2B FOREIGN KEY (dashboard_id) REFERENCES dashboard (id)');
        $this->addSql('CREATE INDEX IDX_27B7D34AB9D04D2B ON posseder_droit_dash (dashboard_id)');
    }
}
