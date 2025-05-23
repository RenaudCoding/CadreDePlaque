<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250523064721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exemplaire DROP FOREIGN KEY FK_5EF83C92A76ED395');
        $this->addSql('ALTER TABLE exemplaire MODIFY user_id INT NOT NULL');
        $this->addSql('ALTER TABLE exemplaire ADD CONSTRAINT FK_5EF83C92A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql(<<<'SQL'
            ALTER TABLE exemplaire CHANGE user_id user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier CHANGE commande_id commande_id INT DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE panier CHANGE commande_id commande_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exemplaire CHANGE user_id user_id INT DEFAULT NULL
        SQL);
    }
}
