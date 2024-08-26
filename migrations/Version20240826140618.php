<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240826140618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lieux ADD ville_id INT NOT NULL, CHANGE rue rue VARCHAR(30) DEFAULT NULL, CHANGE latitude latitude DOUBLE PRECISION DEFAULT NULL, CHANGE longitude longitude DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE lieux ADD CONSTRAINT FK_9E44A8AEA73F0036 FOREIGN KEY (ville_id) REFERENCES villes (id)');
        $this->addSql('CREATE INDEX IDX_9E44A8AEA73F0036 ON lieux (ville_id)');
        $this->addSql('ALTER TABLE participants CHANGE telephone telephone VARCHAR(15) DEFAULT NULL');
        $this->addSql('ALTER TABLE sorties ADD etat_id INT NOT NULL, CHANGE description_infos description_infos VARCHAR(500) DEFAULT NULL, CHANGE url_photo url_photo VARCHAR(250) DEFAULT NULL');
        $this->addSql('ALTER TABLE sorties ADD CONSTRAINT FK_488163E8D5E86FF FOREIGN KEY (etat_id) REFERENCES etats (id)');
        $this->addSql('CREATE INDEX IDX_488163E8D5E86FF ON sorties (etat_id)');
        $this->addSql('ALTER TABLE villes DROP no_ville');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lieux DROP FOREIGN KEY FK_9E44A8AEA73F0036');
        $this->addSql('DROP INDEX IDX_9E44A8AEA73F0036 ON lieux');
        $this->addSql('ALTER TABLE lieux DROP ville_id, CHANGE rue rue VARCHAR(30) DEFAULT \'NULL\', CHANGE latitude latitude DOUBLE PRECISION DEFAULT \'NULL\', CHANGE longitude longitude DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE participants CHANGE telephone telephone VARCHAR(15) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE sorties DROP FOREIGN KEY FK_488163E8D5E86FF');
        $this->addSql('DROP INDEX IDX_488163E8D5E86FF ON sorties');
        $this->addSql('ALTER TABLE sorties DROP etat_id, CHANGE description_infos description_infos VARCHAR(500) DEFAULT \'NULL\', CHANGE url_photo url_photo VARCHAR(250) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE villes ADD no_ville INT NOT NULL');
    }
}
