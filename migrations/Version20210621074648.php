<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210621074648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `character` (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(80) NOT NULL, lastname VARCHAR(80) NOT NULL, gender VARCHAR(100) NOT NULL, bio LONGTEXT DEFAULT NULL, age SMALLINT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE episode (id INT AUTO_INCREMENT NOT NULL, season_id INT NOT NULL, episode_number SMALLINT NOT NULL, title VARCHAR(80) NOT NULL, published_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_DDAA1CDA4EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season (id INT AUTO_INCREMENT NOT NULL, tv_show_id INT NOT NULL, season_number SMALLINT NOT NULL, published_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_F0E45BA95E3A35BB (tv_show_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tv_show (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(80) NOT NULL, synopsis LONGTEXT NOT NULL, image VARCHAR(80) DEFAULT NULL, nb_likes INT DEFAULT NULL, published_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tv_show_character (tv_show_id INT NOT NULL, character_id INT NOT NULL, INDEX IDX_FAF8B7A85E3A35BB (tv_show_id), INDEX IDX_FAF8B7A81136BE75 (character_id), PRIMARY KEY(tv_show_id, character_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tv_show_category (tv_show_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_82897B525E3A35BB (tv_show_id), INDEX IDX_82897B5212469DE2 (category_id), PRIMARY KEY(tv_show_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA95E3A35BB FOREIGN KEY (tv_show_id) REFERENCES tv_show (id)');
        $this->addSql('ALTER TABLE tv_show_character ADD CONSTRAINT FK_FAF8B7A85E3A35BB FOREIGN KEY (tv_show_id) REFERENCES tv_show (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tv_show_character ADD CONSTRAINT FK_FAF8B7A81136BE75 FOREIGN KEY (character_id) REFERENCES `character` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tv_show_category ADD CONSTRAINT FK_82897B525E3A35BB FOREIGN KEY (tv_show_id) REFERENCES tv_show (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tv_show_category ADD CONSTRAINT FK_82897B5212469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tv_show_category DROP FOREIGN KEY FK_82897B5212469DE2');
        $this->addSql('ALTER TABLE tv_show_character DROP FOREIGN KEY FK_FAF8B7A81136BE75');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA4EC001D1');
        $this->addSql('ALTER TABLE season DROP FOREIGN KEY FK_F0E45BA95E3A35BB');
        $this->addSql('ALTER TABLE tv_show_character DROP FOREIGN KEY FK_FAF8B7A85E3A35BB');
        $this->addSql('ALTER TABLE tv_show_category DROP FOREIGN KEY FK_82897B525E3A35BB');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE `character`');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE season');
        $this->addSql('DROP TABLE tv_show');
        $this->addSql('DROP TABLE tv_show_character');
        $this->addSql('DROP TABLE tv_show_category');
    }
}
