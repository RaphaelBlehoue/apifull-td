<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180123160822 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, user_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', type VARCHAR(20) DEFAULT NULL, status_read TINYINT(1) DEFAULT NULL, origin VARCHAR(20) DEFAULT NULL, created DATETIME DEFAULT NULL, subject VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, actor VARCHAR(50) DEFAULT NULL, INDEX IDX_6000B0D3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE notification');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id CHAR(36) DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\', type VARCHAR(20) DEFAULT NULL COLLATE utf8_unicode_ci, status_read TINYINT(1) DEFAULT NULL, origin VARCHAR(20) DEFAULT NULL COLLATE utf8_unicode_ci, created DATETIME DEFAULT NULL, subject VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, content LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, actor VARCHAR(50) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_BF5476CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE notifications');
    }
}
