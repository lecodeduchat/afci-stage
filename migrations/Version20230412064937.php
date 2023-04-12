<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230412064937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE code_postal');
        $this->addSql('ALTER TABLE acts ADD CONSTRAINT FK_6A10A6772989F1FD FOREIGN KEY (invoice_id) REFERENCES invoices (id)');
        $this->addSql('ALTER TABLE acts ADD CONSTRAINT FK_6A10A677E5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointments (id)');
        $this->addSql('ALTER TABLE appointments ADD CONSTRAINT FK_6A41727AF270FD45 FOREIGN KEY (care_id) REFERENCES cares (id)');
        $this->addSql('ALTER TABLE appointments ADD CONSTRAINT FK_6A41727AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE childs ADD CONSTRAINT FK_599EE433861B2665 FOREIGN KEY (parent1_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE childs ADD CONSTRAINT FK_599EE43394AE898B FOREIGN KEY (parent2_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clients (id_client INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, phone VARCHAR(17) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, adresse VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, zip_code VARCHAR(5) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, ville VARCHAR(150) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, birthday DATE NOT NULL, parent_id INT NOT NULL, type_relation VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id_client)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE code_postal (id INT AUTO_INCREMENT NOT NULL, id_code_postal INT NOT NULL, code_postal VARCHAR(11) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE acts DROP FOREIGN KEY FK_6A10A6772989F1FD');
        $this->addSql('ALTER TABLE acts DROP FOREIGN KEY FK_6A10A677E5B533F9');
        $this->addSql('ALTER TABLE appointments DROP FOREIGN KEY FK_6A41727AF270FD45');
        $this->addSql('ALTER TABLE appointments DROP FOREIGN KEY FK_6A41727AA76ED395');
        $this->addSql('ALTER TABLE childs DROP FOREIGN KEY FK_599EE433861B2665');
        $this->addSql('ALTER TABLE childs DROP FOREIGN KEY FK_599EE43394AE898B');
    }
}
