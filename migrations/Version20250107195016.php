<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250107195016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, name_competence VARCHAR(255) DEFAULT NULL, level VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personne_tache (personne_id INT NOT NULL, tache_id INT NOT NULL, INDEX IDX_8766311CA21BD112 (personne_id), INDEX IDX_8766311CD2235D39 (tache_id), PRIMARY KEY(personne_id, tache_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personne_competence (personne_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_6EDD207BA21BD112 (personne_id), INDEX IDX_6EDD207B15761DAB (competence_id), PRIMARY KEY(personne_id, competence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) DEFAULT NULL, name_rs VARCHAR(255) DEFAULT NULL, personne VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tache (id INT AUTO_INCREMENT NOT NULL, name_task VARCHAR(255) DEFAULT NULL, dead_line VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE personne_tache ADD CONSTRAINT FK_8766311CA21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personne_tache ADD CONSTRAINT FK_8766311CD2235D39 FOREIGN KEY (tache_id) REFERENCES tache (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personne_competence ADD CONSTRAINT FK_6EDD207BA21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personne_competence ADD CONSTRAINT FK_6EDD207B15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personne ADD profile_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EFCCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FCEC9EFCCFA12B8 ON personne (profile_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE personne DROP FOREIGN KEY FK_FCEC9EFCCFA12B8');
        $this->addSql('ALTER TABLE personne_tache DROP FOREIGN KEY FK_8766311CA21BD112');
        $this->addSql('ALTER TABLE personne_tache DROP FOREIGN KEY FK_8766311CD2235D39');
        $this->addSql('ALTER TABLE personne_competence DROP FOREIGN KEY FK_6EDD207BA21BD112');
        $this->addSql('ALTER TABLE personne_competence DROP FOREIGN KEY FK_6EDD207B15761DAB');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE personne_tache');
        $this->addSql('DROP TABLE personne_competence');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE tache');
        $this->addSql('DROP INDEX UNIQ_FCEC9EFCCFA12B8 ON personne');
        $this->addSql('ALTER TABLE personne DROP profile_id');
    }
}
