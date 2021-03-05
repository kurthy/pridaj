<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190920140412 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

//        $this->addSql('DROP TABLE lkpzoospecies_avesbeznajc');
//        $this->addSql('ALTER TABLE zoology ADD zoology_export VARCHAR(1) DEFAULT NULL');
//        $this->addSql('ALTER TABLE zoology ADD CONSTRAINT FK_6AAFD564C177E71 FOREIGN KEY (lkpzoospecies_id) REFERENCES lkpzoospecies_aves (id)');
//        $this->addSql('ALTER TABLE zoology ADD CONSTRAINT FK_6AAFD56F538A766 FOREIGN KEY (lkpzoochar_id) REFERENCES lkpzoochar (id)');
//        $this->addSql('CREATE INDEX IDX_6AAFD564C177E71 ON zoology (lkpzoospecies_id)');
//        $this->addSql('CREATE INDEX IDX_6AAFD56F538A766 ON zoology (lkpzoochar_id)');
//        $this->addSql('ALTER TABLE user CHANGE api_token api_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

 //       $this->addSql('CREATE TABLE lkpzoospecies_avesbeznajc (id INT AUTO_INCREMENT NOT NULL, lkpzoospecies_genus_species VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'Contains genus and species of bird\', lkpzoospecies_lat VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, lkpzoospecies_sk VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, lkpzoospecies_dynamic_id INT NOT NULL, lkpzoospecies_subspecorder SMALLINT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
  //      $this->addSql('ALTER TABLE user CHANGE api_token api_token VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
   //     $this->addSql('ALTER TABLE zoology DROP FOREIGN KEY FK_6AAFD564C177E71');
    //    $this->addSql('ALTER TABLE zoology DROP FOREIGN KEY FK_6AAFD56F538A766');
    //    $this->addSql('DROP INDEX IDX_6AAFD564C177E71 ON zoology');
    //    $this->addSql('DROP INDEX IDX_6AAFD56F538A766 ON zoology');
    //    $this->addSql('ALTER TABLE zoology DROP zoology_export');
    }
}
