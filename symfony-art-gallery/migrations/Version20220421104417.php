<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220421104417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE srcatches_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE srcatches (id INT NOT NULL, painter_id INT DEFAULT NULL, picture_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A27CCCAD3A137FE ON srcatches (painter_id)');
        $this->addSql('CREATE INDEX IDX_A27CCCAEE45BDBF ON srcatches (picture_id)');
        $this->addSql('ALTER TABLE srcatches ADD CONSTRAINT FK_A27CCCAD3A137FE FOREIGN KEY (painter_id) REFERENCES painters (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE srcatches ADD CONSTRAINT FK_A27CCCAEE45BDBF FOREIGN KEY (picture_id) REFERENCES pictures (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pictures ALTER kind DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE srcatches_id_seq CASCADE');
        $this->addSql('DROP TABLE srcatches');
        $this->addSql('ALTER TABLE pictures ALTER kind SET DEFAULT \'picture\'');
    }
}
