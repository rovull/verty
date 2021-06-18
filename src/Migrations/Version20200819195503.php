<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200819195503 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE announcement (id INT AUTO_INCREMENT NOT NULL, appeal VARCHAR(180) NOT NULL, title VARCHAR(180) NOT NULL, name VARCHAR(180) NOT NULL, surname VARCHAR(180) NOT NULL, release_date VARCHAR(180) NOT NULL, birth_date VARCHAR(180) NOT NULL, death_date VARCHAR(180) NOT NULL, cemetery_address VARCHAR(180) NOT NULL, email_addresses VARCHAR(180) NOT NULL, pdf_file VARCHAR(180) NOT NULL, user_id VARCHAR(180) NOT NULL, picture VARCHAR(180) NOT NULL, text_to_speak VARCHAR(180) NOT NULL, city VARCHAR(180) NOT NULL, status TINYINT(1) NOT NULL, ceremony_date VARCHAR(180) NOT NULL, ceremony_time VARCHAR(180) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devices (id INT AUTO_INCREMENT NOT NULL, token_device VARCHAR(180) NOT NULL, setting VARCHAR(180) NOT NULL, type VARCHAR(180) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE instructions (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoices (id INT AUTO_INCREMENT NOT NULL, invoice_id VARCHAR(180) NOT NULL, company VARCHAR(180) NOT NULL, name VARCHAR(180) NOT NULL, request_date VARCHAR(180) NOT NULL, sum INT NOT NULL, paid TINYINT(1) NOT NULL, pdf VARCHAR(180) NOT NULL, user_id VARCHAR(180) NOT NULL, preview VARCHAR(180) NOT NULL, surname VARCHAR(180) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE policy (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE terms (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(180) NOT NULL, surname VARCHAR(180) NOT NULL, legal_form VARCHAR(180) NOT NULL, place VARCHAR(180) NOT NULL, company VARCHAR(180) NOT NULL, street VARCHAR(180) NOT NULL, postcode_city VARCHAR(180) NOT NULL, phone VARCHAR(180) NOT NULL, undertaker_id VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, roles VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, request_date VARCHAR(255) NOT NULL, deactivate TINYINT(1) NOT NULL, register_date VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E565C74 (undertaker_id), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cron_job (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(191) NOT NULL, command VARCHAR(1024) NOT NULL, schedule VARCHAR(191) NOT NULL, description VARCHAR(191) NOT NULL, enabled TINYINT(1) NOT NULL, UNIQUE INDEX un_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cron_report (id INT AUTO_INCREMENT NOT NULL, job_id INT DEFAULT NULL, run_at DATETIME NOT NULL, run_time DOUBLE PRECISION NOT NULL, exit_code INT NOT NULL, output LONGTEXT NOT NULL, INDEX IDX_B6C6A7F5BE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cron_report ADD CONSTRAINT FK_B6C6A7F5BE04EA9 FOREIGN KEY (job_id) REFERENCES cron_job (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cron_report DROP FOREIGN KEY FK_B6C6A7F5BE04EA9');
        $this->addSql('DROP TABLE announcement');
        $this->addSql('DROP TABLE devices');
        $this->addSql('DROP TABLE instructions');
        $this->addSql('DROP TABLE invoices');
        $this->addSql('DROP TABLE policy');
        $this->addSql('DROP TABLE terms');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE cron_job');
        $this->addSql('DROP TABLE cron_report');
    }
}
