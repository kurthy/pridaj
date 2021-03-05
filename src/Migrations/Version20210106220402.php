<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210106220402 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('DROP TABLE ebirdtaxv2019');
//        $this->addSql('ALTER TABLE user ADD ebdisnam VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        //$this->addSql('CREATE TABLE ebirdtaxv2019 (TAXON_ORDER INT DEFAULT NULL, CATEGORY VARCHAR(10) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, SPECIES_CODE VARCHAR(8) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, PRIMARY_COM_NAME VARCHAR(59) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, SCI_NAME VARCHAR(60) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, ORDER1 VARCHAR(19) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, FAMILY VARCHAR(57) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, SPECIES_GROUP VARCHAR(53) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, REPORT_AS VARCHAR(8) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
 //       $this->addSql('ALTER TABLE user DROP ebdisnam');
    }
}
