<?php
/**
 * This file is part of test task
 */

namespace Application\Migrations\Base;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * @author Evgeny Sapozhnikov <zsapozhnikov@gmail.com>
 */
class Version20161010214927 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE gpe_album_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE gpe_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE gpe_album (id INT NOT NULL, title VARCHAR(255) NOT NULL, sort_order INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE gpe_image (id INT NOT NULL, album_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, sort_order INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_551243711137ABCF ON gpe_image (album_id)');
        $this->addSql('ALTER TABLE gpe_image ADD CONSTRAINT FK_551243711137ABCF FOREIGN KEY (album_id) REFERENCES gpe_album (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE gpe_image DROP CONSTRAINT FK_551243711137ABCF');
        $this->addSql('DROP SEQUENCE gpe_album_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE gpe_image_id_seq CASCADE');
        $this->addSql('DROP TABLE gpe_album');
        $this->addSql('DROP TABLE gpe_image');
    }
}
