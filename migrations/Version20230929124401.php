<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230929124401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contract_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, INDEX IDX_6A2CA10C67B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sector (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date_created DATE NOT NULL, is_completed TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_user (task_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FE2042328DB60186 (task_id), INDEX IDX_FE204232A76ED395 (user_id), PRIMARY KEY(task_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, contract_type_id INT NOT NULL, sector_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, date_created DATE NOT NULL, date_finish DATE DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649CD1DF15B (contract_type_id), INDEX IDX_8D93D649DE95C867 (sector_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C67B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task_user ADD CONSTRAINT FK_FE2042328DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_user ADD CONSTRAINT FK_FE204232A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CD1DF15B FOREIGN KEY (contract_type_id) REFERENCES contract_type (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DE95C867 FOREIGN KEY (sector_id) REFERENCES sector (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C67B3B43D');
        $this->addSql('ALTER TABLE task_user DROP FOREIGN KEY FK_FE2042328DB60186');
        $this->addSql('ALTER TABLE task_user DROP FOREIGN KEY FK_FE204232A76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CD1DF15B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DE95C867');
        $this->addSql('DROP TABLE contract_type');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE sector');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_user');
        $this->addSql('DROP TABLE user');
    }
}
