<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240826141252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participants DROP no_participant');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7169709286CC499D ON participants (pseudo)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_716970925126AC48 ON participants (mail)');
        $this->addSql('ALTER TABLE sorties CHANGE no_sortie organisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE sorties ADD CONSTRAINT FK_488163E8D936B2FA FOREIGN KEY (organisateur_id) REFERENCES participants (id)');
        $this->addSql('CREATE INDEX IDX_488163E8D936B2FA ON sorties (organisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sorties DROP FOREIGN KEY FK_488163E8D936B2FA');
        $this->addSql('DROP INDEX IDX_488163E8D936B2FA ON sorties');
        $this->addSql('ALTER TABLE sorties CHANGE organisateur_id no_sortie INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_7169709286CC499D ON participants');
        $this->addSql('DROP INDEX UNIQ_716970925126AC48 ON participants');
        $this->addSql('ALTER TABLE participants ADD no_participant INT NOT NULL');
    }
}
