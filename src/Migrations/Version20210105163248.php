<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210105163248 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //$this->addSql('DROP TABLE eBird taxonomy v2019');
//        $this->addSql('DROP TABLE migration_versions');
//        $this->addSql('ALTER TABLE lkpzoospecies_aves ADD lkpzoospecies_ebirdcode VARCHAR(8) DEFAULT NULL');
        //$this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
   //     $this->addSql('CREATE TABLE eBird taxonomy v2019 (TAXON_ORDER INT DEFAULT NULL, CATEGORY VARCHAR(10) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, SPECIES_CODE VARCHAR(8) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, PRIMARY_COM_NAME VARCHAR(59) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, SCI_NAME VARCHAR(60) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, ORDER1 VARCHAR(19) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, FAMILY VARCHAR(57) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, SPECIES_GROUP VARCHAR(53) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, REPORT_AS VARCHAR(8) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
 //       $this->addSql('CREATE TABLE migration_versions (version VARCHAR(14) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, executed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(version)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE lkpzoospecies_aves DROP lkpzoospecies_ebirdcode');
    //    $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
