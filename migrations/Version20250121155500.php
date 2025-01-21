<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250121155500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profil_user (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, adress VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_85CBC6ABA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects_profil_user (projects_id INT NOT NULL, profil_user_id INT NOT NULL, INDEX IDX_D81562381EDE0F55 (projects_id), INDEX IDX_D8156238227A1CC4 (profil_user_id), PRIMARY KEY(projects_id, profil_user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_profil_user (task_id INT NOT NULL, profil_user_id INT NOT NULL, INDEX IDX_C3BE6B998DB60186 (task_id), INDEX IDX_C3BE6B99227A1CC4 (profil_user_id), PRIMARY KEY(task_id, profil_user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE profil_user ADD CONSTRAINT FK_85CBC6ABA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE projects_profil_user ADD CONSTRAINT FK_D81562381EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects_profil_user ADD CONSTRAINT FK_D8156238227A1CC4 FOREIGN KEY (profil_user_id) REFERENCES profil_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_profil_user ADD CONSTRAINT FK_C3BE6B998DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_profil_user ADD CONSTRAINT FK_C3BE6B99227A1CC4 FOREIGN KEY (profil_user_id) REFERENCES profil_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects_user DROP FOREIGN KEY FK_B38D6A811EDE0F55');
        $this->addSql('ALTER TABLE projects_user DROP FOREIGN KEY FK_B38D6A81A76ED395');
        $this->addSql('ALTER TABLE task_user DROP FOREIGN KEY FK_FE2042328DB60186');
        $this->addSql('ALTER TABLE task_user DROP FOREIGN KEY FK_FE204232A76ED395');
        $this->addSql('DROP TABLE projects_user');
        $this->addSql('DROP TABLE task_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE projects_user (projects_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B38D6A811EDE0F55 (projects_id), INDEX IDX_B38D6A81A76ED395 (user_id), PRIMARY KEY(projects_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE task_user (task_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FE2042328DB60186 (task_id), INDEX IDX_FE204232A76ED395 (user_id), PRIMARY KEY(task_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE projects_user ADD CONSTRAINT FK_B38D6A811EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects_user ADD CONSTRAINT FK_B38D6A81A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_user ADD CONSTRAINT FK_FE2042328DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_user ADD CONSTRAINT FK_FE204232A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_user DROP FOREIGN KEY FK_85CBC6ABA76ED395');
        $this->addSql('ALTER TABLE projects_profil_user DROP FOREIGN KEY FK_D81562381EDE0F55');
        $this->addSql('ALTER TABLE projects_profil_user DROP FOREIGN KEY FK_D8156238227A1CC4');
        $this->addSql('ALTER TABLE task_profil_user DROP FOREIGN KEY FK_C3BE6B998DB60186');
        $this->addSql('ALTER TABLE task_profil_user DROP FOREIGN KEY FK_C3BE6B99227A1CC4');
        $this->addSql('DROP TABLE profil_user');
        $this->addSql('DROP TABLE projects_profil_user');
        $this->addSql('DROP TABLE task_profil_user');
    }
}
