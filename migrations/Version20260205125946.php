<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260205125946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
        $this->addSql('CREATE TABLE user_card (user_id INTEGER NOT NULL, card_id INTEGER NOT NULL, PRIMARY KEY (user_id, card_id), CONSTRAINT FK_6C95D41AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6C95D41A4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_6C95D41AA76ED395 ON user_card (user_id)');
        $this->addSql('CREATE INDEX IDX_6C95D41A4ACC9A20 ON user_card (card_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_session AS SELECT id, start_time, current_hider_id FROM game_session');
        $this->addSql('DROP TABLE game_session');
        $this->addSql('CREATE TABLE game_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, start_time DATE DEFAULT NULL, current_hider_id INTEGER DEFAULT NULL, hider_bonus_time INTEGER DEFAULT 0 NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_4586AAFB7FFBD63F FOREIGN KEY (current_hider_id) REFERENCES player (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4586AAFBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO game_session (id, start_time, current_hider_id) SELECT id, start_time, current_hider_id FROM __temp__game_session');
        $this->addSql('DROP TABLE __temp__game_session');
        $this->addSql('CREATE INDEX IDX_4586AAFB7FFBD63F ON game_session (current_hider_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4586AAFBA76ED395 ON game_session (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_card');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_session AS SELECT id, start_time, current_hider_id FROM game_session');
        $this->addSql('DROP TABLE game_session');
        $this->addSql('CREATE TABLE game_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, start_time DATE DEFAULT NULL, current_hider_id INTEGER DEFAULT NULL, CONSTRAINT FK_4586AAFB7FFBD63F FOREIGN KEY (current_hider_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO game_session (id, start_time, current_hider_id) SELECT id, start_time, current_hider_id FROM __temp__game_session');
        $this->addSql('DROP TABLE __temp__game_session');
        $this->addSql('CREATE INDEX IDX_4586AAFB7FFBD63F ON game_session (current_hider_id)');
    }
}
