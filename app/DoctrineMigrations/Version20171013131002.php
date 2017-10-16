<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171013131002 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users DROP is_password_default, CHANGE username username VARCHAR(100) DEFAULT NULL COMMENT \'Prend la valeur de l\'\'email\', CHANGE is_active is_active TINYINT(1) DEFAULT NULL COMMENT \'Mode d\'\'activation du compte en fonction du client\', CHANGE code_validation code_validation INT DEFAULT NULL COMMENT \'Code validation envoyez sur le téléphone et le mail d\'\'un vendeur.\', CHANGE created created DATETIME NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users ADD is_password_default TINYINT(1) DEFAULT NULL, CHANGE username username VARCHAR(25) NOT NULL COLLATE utf8_unicode_ci, CHANGE code_validation code_validation INT DEFAULT NULL, CHANGE created created DATETIME DEFAULT NULL, CHANGE is_active is_active TINYINT(1) DEFAULT NULL');
    }
}
