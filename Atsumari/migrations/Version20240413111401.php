<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240413111401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE author_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE book_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE genre_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reading_session_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_book_stats_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_details_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE author (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE book (id INT NOT NULL, title VARCHAR(255) NOT NULL, num_of_pages INT NOT NULL, cover_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE book_genre (book_id INT NOT NULL, genre_id INT NOT NULL, PRIMARY KEY(book_id, genre_id))');
        $this->addSql('CREATE INDEX IDX_8D92268116A2B381 ON book_genre (book_id)');
        $this->addSql('CREATE INDEX IDX_8D9226814296D31F ON book_genre (genre_id)');
        $this->addSql('CREATE TABLE book_author (book_id INT NOT NULL, author_id INT NOT NULL, PRIMARY KEY(book_id, author_id))');
        $this->addSql('CREATE INDEX IDX_9478D34516A2B381 ON book_author (book_id)');
        $this->addSql('CREATE INDEX IDX_9478D345F675F31B ON book_author (author_id)');
        $this->addSql('CREATE TABLE genre (id INT NOT NULL, genre_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE reading_session (id INT NOT NULL, user_id_id INT NOT NULL, book_id_id INT NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, pages_read INT NOT NULL, duration INT NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_94DD46E19D86650F ON reading_session (user_id_id)');
        $this->addSql('CREATE INDEX IDX_94DD46E171868B2E ON reading_session (book_id_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, id_user_details_id INT NOT NULL, user_type_id_id INT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F5230246 ON "user" (id_user_details_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649D62FDF4C ON "user" (user_type_id_id)');
        $this->addSql('CREATE TABLE user_book (user_id INT NOT NULL, book_id INT NOT NULL, PRIMARY KEY(user_id, book_id))');
        $this->addSql('CREATE INDEX IDX_B164EFF8A76ED395 ON user_book (user_id)');
        $this->addSql('CREATE INDEX IDX_B164EFF816A2B381 ON user_book (book_id)');
        $this->addSql('CREATE TABLE user_book_stats (id INT NOT NULL, user_id_id INT NOT NULL, book_id_id INT NOT NULL, total_reading_time INT NOT NULL, sessions_count INT NOT NULL, pages_read_count INT NOT NULL, last_session_end TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, reading_speed DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FEE2B43F9D86650F ON user_book_stats (user_id_id)');
        $this->addSql('CREATE INDEX IDX_FEE2B43F71868B2E ON user_book_stats (book_id_id)');
        $this->addSql('CREATE TABLE user_details (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_type (id INT NOT NULL, type_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE book_genre ADD CONSTRAINT FK_8D92268116A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_genre ADD CONSTRAINT FK_8D9226814296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_author ADD CONSTRAINT FK_9478D34516A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_author ADD CONSTRAINT FK_9478D345F675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reading_session ADD CONSTRAINT FK_94DD46E19D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reading_session ADD CONSTRAINT FK_94DD46E171868B2E FOREIGN KEY (book_id_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649F5230246 FOREIGN KEY (id_user_details_id) REFERENCES user_details (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649D62FDF4C FOREIGN KEY (user_type_id_id) REFERENCES user_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_book ADD CONSTRAINT FK_B164EFF8A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_book ADD CONSTRAINT FK_B164EFF816A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_book_stats ADD CONSTRAINT FK_FEE2B43F9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_book_stats ADD CONSTRAINT FK_FEE2B43F71868B2E FOREIGN KEY (book_id_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE author_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE book_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE genre_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reading_session_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE user_book_stats_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_details_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_type_id_seq CASCADE');
        $this->addSql('ALTER TABLE book_genre DROP CONSTRAINT FK_8D92268116A2B381');
        $this->addSql('ALTER TABLE book_genre DROP CONSTRAINT FK_8D9226814296D31F');
        $this->addSql('ALTER TABLE book_author DROP CONSTRAINT FK_9478D34516A2B381');
        $this->addSql('ALTER TABLE book_author DROP CONSTRAINT FK_9478D345F675F31B');
        $this->addSql('ALTER TABLE reading_session DROP CONSTRAINT FK_94DD46E19D86650F');
        $this->addSql('ALTER TABLE reading_session DROP CONSTRAINT FK_94DD46E171868B2E');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649F5230246');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649D62FDF4C');
        $this->addSql('ALTER TABLE user_book DROP CONSTRAINT FK_B164EFF8A76ED395');
        $this->addSql('ALTER TABLE user_book DROP CONSTRAINT FK_B164EFF816A2B381');
        $this->addSql('ALTER TABLE user_book_stats DROP CONSTRAINT FK_FEE2B43F9D86650F');
        $this->addSql('ALTER TABLE user_book_stats DROP CONSTRAINT FK_FEE2B43F71868B2E');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE book_genre');
        $this->addSql('DROP TABLE book_author');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE reading_session');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_book');
        $this->addSql('DROP TABLE user_book_stats');
        $this->addSql('DROP TABLE user_details');
        $this->addSql('DROP TABLE user_type');
    }
}
