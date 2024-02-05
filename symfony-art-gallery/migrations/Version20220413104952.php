<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220413104952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE currency_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE exchange_info_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE picture_purchase_reports_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE pictures_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tags_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ticket_purchase_reports_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE currency (id INT NOT NULL, code VARCHAR(3) NOT NULL, name VARCHAR(3) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX currency_code_unq ON currency (code)');
        $this->addSql('CREATE UNIQUE INDEX currency_name_unq ON currency (name)');
        $this->addSql('CREATE TABLE exchange_info (id INT NOT NULL, first_purchase_id INT DEFAULT NULL, second_purchase_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD3711C5CB7AB37B ON exchange_info (first_purchase_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD3711C53C6A22A7 ON exchange_info (second_purchase_id)');
        $this->addSql('CREATE TABLE galleries (id INT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX galleries_name_idx ON galleries (name)');
        $this->addSql('CREATE INDEX galleries_address_idx ON galleries (address)');
        $this->addSql('CREATE TABLE painters (id INT NOT NULL, full_name VARCHAR(255) NOT NULL, date_of_birth DATE DEFAULT NULL, telephone_number VARCHAR(30) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX painters_full_name_idx ON painters (full_name)');
        $this->addSql('CREATE INDEX painters_telephone_number_idx ON painters (telephone_number)');
        $this->addSql('COMMENT ON COLUMN painters.date_of_birth IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE picture_purchase_reports (id INT NOT NULL, visitor_id INT DEFAULT NULL, picture_id INT DEFAULT NULL, currency_id INT DEFAULT NULL, date_of_purchase TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, cost INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B83003D070BEE6D ON picture_purchase_reports (visitor_id)');
        $this->addSql('CREATE INDEX IDX_B83003D0EE45BDBF ON picture_purchase_reports (picture_id)');
        $this->addSql('CREATE INDEX IDX_B83003D038248176 ON picture_purchase_reports (currency_id)');
        $this->addSql('CREATE INDEX picture_purchase_reports_cost_idx ON picture_purchase_reports (cost)');
        $this->addSql('CREATE INDEX picture_purchase_reports_date_of_purchase_idx ON picture_purchase_reports (date_of_purchase)');
        $this->addSql('COMMENT ON COLUMN picture_purchase_reports.date_of_purchase IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE pictures (id INT NOT NULL, painter_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, year DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8F7C2FC0D3A137FE ON pictures (painter_id)');
        $this->addSql('CREATE INDEX pictures_name_idx ON pictures (name)');
        $this->addSql('CREATE INDEX pictures_year_idx ON pictures (year)');
        $this->addSql('COMMENT ON COLUMN pictures.year IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE tags_to_pictures (picture_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(picture_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_954ADA9CEE45BDBF ON tags_to_pictures (picture_id)');
        $this->addSql('CREATE INDEX IDX_954ADA9CBAD26311 ON tags_to_pictures (tag_id)');
        $this->addSql('CREATE TABLE tags (id INT NOT NULL, name VARCHAR(25) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX tags_name_unq ON tags (name)');
        $this->addSql('CREATE TABLE ticket_purchase_reports (id INT NOT NULL, visitor_id INT DEFAULT NULL, ticket_id INT DEFAULT NULL, currency_id INT DEFAULT NULL, date_of_purchase TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, cost INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8077D9A570BEE6D ON ticket_purchase_reports (visitor_id)');
        $this->addSql('CREATE INDEX IDX_8077D9A5700047D2 ON ticket_purchase_reports (ticket_id)');
        $this->addSql('CREATE INDEX IDX_8077D9A538248176 ON ticket_purchase_reports (currency_id)');
        $this->addSql('CREATE INDEX ticket_purchase_reports_cost_idx ON ticket_purchase_reports (cost)');
        $this->addSql('CREATE INDEX ticket_purchase_reports_date_of_purchase_idx ON ticket_purchase_reports (date_of_purchase)');
        $this->addSql('COMMENT ON COLUMN ticket_purchase_reports.date_of_purchase IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE tickets (id INT NOT NULL, gallery_id INT DEFAULT NULL, currency_id INT DEFAULT NULL, date_of_visit DATE NOT NULL, cost INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_54469DF44E7AF8F ON tickets (gallery_id)');
        $this->addSql('CREATE INDEX IDX_54469DF438248176 ON tickets (currency_id)');
        $this->addSql('CREATE INDEX tickets_cost_idx ON tickets (cost)');
        $this->addSql('CREATE INDEX tickets_date_of_visit_idx ON tickets (date_of_visit)');
        $this->addSql('COMMENT ON COLUMN tickets.date_of_visit IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, full_name VARCHAR(255) NOT NULL, date_of_birth DATE NOT NULL, telephone_number VARCHAR(30) NOT NULL, role VARCHAR(60) NOT NULL, login VARCHAR(50) DEFAULT NULL, password VARCHAR(60) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX users_full_name_idx ON users (full_name)');
        $this->addSql('CREATE INDEX users_telephone_number_idx ON users (telephone_number)');
        $this->addSql('CREATE UNIQUE INDEX users_login_unq ON users (login)');
        $this->addSql('COMMENT ON COLUMN users.date_of_birth IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE exchange_info ADD CONSTRAINT FK_FD3711C5CB7AB37B FOREIGN KEY (first_purchase_id) REFERENCES picture_purchase_reports (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE exchange_info ADD CONSTRAINT FK_FD3711C53C6A22A7 FOREIGN KEY (second_purchase_id) REFERENCES picture_purchase_reports (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE picture_purchase_reports ADD CONSTRAINT FK_B83003D070BEE6D FOREIGN KEY (visitor_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE picture_purchase_reports ADD CONSTRAINT FK_B83003D0EE45BDBF FOREIGN KEY (picture_id) REFERENCES pictures (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE picture_purchase_reports ADD CONSTRAINT FK_B83003D038248176 FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pictures ADD CONSTRAINT FK_8F7C2FC0D3A137FE FOREIGN KEY (painter_id) REFERENCES painters (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tags_to_pictures ADD CONSTRAINT FK_954ADA9CEE45BDBF FOREIGN KEY (picture_id) REFERENCES pictures (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tags_to_pictures ADD CONSTRAINT FK_954ADA9CBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket_purchase_reports ADD CONSTRAINT FK_8077D9A570BEE6D FOREIGN KEY (visitor_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket_purchase_reports ADD CONSTRAINT FK_8077D9A5700047D2 FOREIGN KEY (ticket_id) REFERENCES tickets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket_purchase_reports ADD CONSTRAINT FK_8077D9A538248176 FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF44E7AF8F FOREIGN KEY (gallery_id) REFERENCES galleries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF438248176 FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE picture_purchase_reports DROP CONSTRAINT FK_B83003D038248176');
        $this->addSql('ALTER TABLE ticket_purchase_reports DROP CONSTRAINT FK_8077D9A538248176');
        $this->addSql('ALTER TABLE tickets DROP CONSTRAINT FK_54469DF438248176');
        $this->addSql('ALTER TABLE tickets DROP CONSTRAINT FK_54469DF44E7AF8F');
        $this->addSql('ALTER TABLE pictures DROP CONSTRAINT FK_8F7C2FC0D3A137FE');
        $this->addSql('ALTER TABLE exchange_info DROP CONSTRAINT FK_FD3711C5CB7AB37B');
        $this->addSql('ALTER TABLE exchange_info DROP CONSTRAINT FK_FD3711C53C6A22A7');
        $this->addSql('ALTER TABLE picture_purchase_reports DROP CONSTRAINT FK_B83003D0EE45BDBF');
        $this->addSql('ALTER TABLE tags_to_pictures DROP CONSTRAINT FK_954ADA9CEE45BDBF');
        $this->addSql('ALTER TABLE tags_to_pictures DROP CONSTRAINT FK_954ADA9CBAD26311');
        $this->addSql('ALTER TABLE ticket_purchase_reports DROP CONSTRAINT FK_8077D9A5700047D2');
        $this->addSql('ALTER TABLE picture_purchase_reports DROP CONSTRAINT FK_B83003D070BEE6D');
        $this->addSql('ALTER TABLE ticket_purchase_reports DROP CONSTRAINT FK_8077D9A570BEE6D');
        $this->addSql('DROP SEQUENCE currency_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE exchange_info_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE picture_purchase_reports_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE pictures_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tags_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ticket_purchase_reports_id_seq CASCADE');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE exchange_info');
        $this->addSql('DROP TABLE galleries');
        $this->addSql('DROP TABLE painters');
        $this->addSql('DROP TABLE picture_purchase_reports');
        $this->addSql('DROP TABLE pictures');
        $this->addSql('DROP TABLE tags_to_pictures');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE ticket_purchase_reports');
        $this->addSql('DROP TABLE tickets');
        $this->addSql('DROP TABLE users');
    }
}
