<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190304035904 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE persona (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_51E5B69BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, location_id INT NOT NULL, game_master_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, goal LONGTEXT DEFAULT NULL, max_playing_personas INT NOT NULL, INDEX IDX_232B318C64D218E (location_id), INDEX IDX_232B318CC1151A13 (game_master_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE games_pendingPersonas (game_id INT NOT NULL, persona_id INT NOT NULL, INDEX IDX_C2A26E90E48FD905 (game_id), INDEX IDX_C2A26E90F5F88DB9 (persona_id), PRIMARY KEY(game_id, persona_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE games_playingPersonas (game_id INT NOT NULL, persona_id INT NOT NULL, INDEX IDX_D933A8F7E48FD905 (game_id), INDEX IDX_D933A8F7F5F88DB9 (persona_id), PRIMARY KEY(game_id, persona_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_play (id INT AUTO_INCREMENT NOT NULL, persona_id INT DEFAULT NULL, game_id INT NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_E036AC2EF5F88DB9 (persona_id), INDEX IDX_E036AC2EE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_5E9E89CB727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CC1151A13 FOREIGN KEY (game_master_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE games_pendingPersonas ADD CONSTRAINT FK_C2A26E90E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE games_pendingPersonas ADD CONSTRAINT FK_C2A26E90F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE games_playingPersonas ADD CONSTRAINT FK_D933A8F7E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE games_playingPersonas ADD CONSTRAINT FK_D933A8F7F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_play ADD CONSTRAINT FK_E036AC2EF5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE role_play ADD CONSTRAINT FK_E036AC2EE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB727ACA70 FOREIGN KEY (parent_id) REFERENCES location (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE games_pendingPersonas DROP FOREIGN KEY FK_C2A26E90F5F88DB9');
        $this->addSql('ALTER TABLE games_playingPersonas DROP FOREIGN KEY FK_D933A8F7F5F88DB9');
        $this->addSql('ALTER TABLE role_play DROP FOREIGN KEY FK_E036AC2EF5F88DB9');
        $this->addSql('ALTER TABLE games_pendingPersonas DROP FOREIGN KEY FK_C2A26E90E48FD905');
        $this->addSql('ALTER TABLE games_playingPersonas DROP FOREIGN KEY FK_D933A8F7E48FD905');
        $this->addSql('ALTER TABLE role_play DROP FOREIGN KEY FK_E036AC2EE48FD905');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C64D218E');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB727ACA70');
        $this->addSql('DROP TABLE persona');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE games_pendingPersonas');
        $this->addSql('DROP TABLE games_playingPersonas');
        $this->addSql('DROP TABLE role_play');
        $this->addSql('DROP TABLE location');
    }
}
