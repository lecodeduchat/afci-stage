<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230428103214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE childs DROP INDEX UNIQ_599EE433861B2665, ADD INDEX IDX_599EE433861B2665 (parent1_id)');
        $this->addSql('ALTER TABLE childs DROP INDEX UNIQ_599EE43394AE898B, ADD INDEX IDX_599EE43394AE898B (parent2_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE childs DROP INDEX IDX_599EE433861B2665, ADD UNIQUE INDEX UNIQ_599EE433861B2665 (parent1_id)');
        $this->addSql('ALTER TABLE childs DROP INDEX IDX_599EE43394AE898B, ADD UNIQUE INDEX UNIQ_599EE43394AE898B (parent2_id)');
    }
}
