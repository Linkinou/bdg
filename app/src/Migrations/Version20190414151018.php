<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190414151018 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, topic_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_5A8A6C8DF675F31B (author_id), INDEX IDX_5A8A6C8D1F55203D (topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, category_id INT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, last_post_created_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_9D40DE1B989D9B62 (slug), INDEX IDX_9D40DE1BF675F31B (author_id), INDEX IDX_9D40DE1B12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_64C19C1989D9B62 (slug), INDEX IDX_64C19C1727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persona (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_51E5B69B989D9B62 (slug), INDEX IDX_51E5B69BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, location_id INT NOT NULL, game_master_id INT NOT NULL, title VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, story LONGTEXT NOT NULL, max_playing_personas INT NOT NULL, slug VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_232B318C989D9B62 (slug), INDEX IDX_232B318C64D218E (location_id), INDEX IDX_232B318CC1151A13 (game_master_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE games_pendingPersonas (game_id INT NOT NULL, persona_id INT NOT NULL, INDEX IDX_C2A26E90E48FD905 (game_id), INDEX IDX_C2A26E90F5F88DB9 (persona_id), PRIMARY KEY(game_id, persona_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE games_playingPersonas (game_id INT NOT NULL, persona_id INT NOT NULL, INDEX IDX_D933A8F7E48FD905 (game_id), INDEX IDX_D933A8F7F5F88DB9 (persona_id), PRIMARY KEY(game_id, persona_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_play (id INT AUTO_INCREMENT NOT NULL, persona_id INT NOT NULL, game_id INT NOT NULL, user_id INT NOT NULL, event_id INT DEFAULT NULL, content LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E036AC2EF5F88DB9 (persona_id), INDEX IDX_E036AC2EE48FD905 (game_id), INDEX IDX_E036AC2EA76ED395 (user_id), UNIQUE INDEX UNIQ_E036AC2E71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_5E9E89CB989D9B62 (slug), INDEX IDX_5E9E89CB727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D1F55203D FOREIGN KEY (topic_id) REFERENCES topic (id)');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1BF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1B12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CC1151A13 FOREIGN KEY (game_master_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE games_pendingPersonas ADD CONSTRAINT FK_C2A26E90E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE games_pendingPersonas ADD CONSTRAINT FK_C2A26E90F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE games_playingPersonas ADD CONSTRAINT FK_D933A8F7E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE games_playingPersonas ADD CONSTRAINT FK_D933A8F7F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_play ADD CONSTRAINT FK_E036AC2EF5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE role_play ADD CONSTRAINT FK_E036AC2EE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE role_play ADD CONSTRAINT FK_E036AC2EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE role_play ADD CONSTRAINT FK_E036AC2E71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB727ACA70 FOREIGN KEY (parent_id) REFERENCES location (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE role_play DROP FOREIGN KEY FK_E036AC2E71F7E88B');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DF675F31B');
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1BF675F31B');
        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69BA76ED395');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CC1151A13');
        $this->addSql('ALTER TABLE role_play DROP FOREIGN KEY FK_E036AC2EA76ED395');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D1F55203D');
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1B12469DE2');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE games_pendingPersonas DROP FOREIGN KEY FK_C2A26E90F5F88DB9');
        $this->addSql('ALTER TABLE games_playingPersonas DROP FOREIGN KEY FK_D933A8F7F5F88DB9');
        $this->addSql('ALTER TABLE role_play DROP FOREIGN KEY FK_E036AC2EF5F88DB9');
        $this->addSql('ALTER TABLE games_pendingPersonas DROP FOREIGN KEY FK_C2A26E90E48FD905');
        $this->addSql('ALTER TABLE games_playingPersonas DROP FOREIGN KEY FK_D933A8F7E48FD905');
        $this->addSql('ALTER TABLE role_play DROP FOREIGN KEY FK_E036AC2EE48FD905');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C64D218E');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB727ACA70');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE topic');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE persona');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE games_pendingPersonas');
        $this->addSql('DROP TABLE games_playingPersonas');
        $this->addSql('DROP TABLE role_play');
        $this->addSql('DROP TABLE location');
    }
}
