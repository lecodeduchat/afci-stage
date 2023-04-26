<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426101152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointments DROP FOREIGN KEY FK_6A41727A2C423CC4');
        $this->addSql('DROP INDEX idx_6a41727a2c423cc4 ON appointments');
        $this->addSql('CREATE INDEX IDX_6A41727ADD62C21B ON appointments (child_id)');
        $this->addSql('ALTER TABLE appointments ADD CONSTRAINT FK_6A41727A2C423CC4 FOREIGN KEY (child_id) REFERENCES childs (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointments DROP FOREIGN KEY FK_6A41727ADD62C21B');
        $this->addSql('DROP INDEX idx_6a41727add62c21b ON appointments');
        $this->addSql('CREATE INDEX IDX_6A41727A2C423CC4 ON appointments (child_id)');
        $this->addSql('ALTER TABLE appointments ADD CONSTRAINT FK_6A41727ADD62C21B FOREIGN KEY (child_id) REFERENCES childs (id)');
    }
}
