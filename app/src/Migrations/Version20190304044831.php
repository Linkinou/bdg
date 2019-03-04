<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190304044831 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE persona ADD slug VARCHAR(128) NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51E5B69B989D9B62 ON persona (slug)');
        $this->addSql('ALTER TABLE game ADD slug VARCHAR(128) NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318C989D9B62 ON game (slug)');
        $this->addSql('ALTER TABLE role_play ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE location ADD slug VARCHAR(128) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E9E89CB989D9B62 ON location (slug)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_232B318C989D9B62 ON game');
        $this->addSql('ALTER TABLE game DROP slug, DROP created_at, DROP updated_at');
        $this->addSql('DROP INDEX UNIQ_5E9E89CB989D9B62 ON location');
        $this->addSql('ALTER TABLE location DROP slug');
        $this->addSql('DROP INDEX UNIQ_51E5B69B989D9B62 ON persona');
        $this->addSql('ALTER TABLE persona DROP slug, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE role_play DROP created_at, DROP updated_at');
    }
}
