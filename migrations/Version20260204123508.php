<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260204123508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_session_question (game_session_id INTEGER NOT NULL, question_id INTEGER NOT NULL, PRIMARY KEY (game_session_id, question_id), CONSTRAINT FK_34AEE3A8FE32B32 FOREIGN KEY (game_session_id) REFERENCES game_session (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_34AEE3A1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_34AEE3A8FE32B32 ON game_session_question (game_session_id)');
        $this->addSql('CREATE INDEX IDX_34AEE3A1E27F6BF ON game_session_question (question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE game_session_question');
    }
}
