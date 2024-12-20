<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241211102708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answers (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_question_id INT NOT NULL, timestamp DATETIME NOT NULL, ch_answer VARCHAR(255) NOT NULL, INDEX IDX_50D0C60679F37AE5 (id_user_id), INDEX IDX_50D0C6066353B48 (id_question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C60679F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C6066353B48 FOREIGN KEY (id_question_id) REFERENCES questions (id)');
        $this->addSql('ALTER TABLE questions DROP answers');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answers DROP FOREIGN KEY FK_50D0C60679F37AE5');
        $this->addSql('ALTER TABLE answers DROP FOREIGN KEY FK_50D0C6066353B48');
        $this->addSql('DROP TABLE answers');
        $this->addSql('ALTER TABLE questions ADD answers VARCHAR(255) NOT NULL');
    }
}
