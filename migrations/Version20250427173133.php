<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250427173133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE base (id INT AUTO_INCREMENT NOT NULL, fond_id INT NOT NULL, exemplaire_id INT NOT NULL, INDEX IDX_C0B4FE6187EEAE74 (fond_id), INDEX IDX_C0B4FE615843AA21 (exemplaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, facture_id INT NOT NULL, date_commande DATETIME NOT NULL, num_commande VARCHAR(50) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, ville VARCHAR(100) NOT NULL, cp VARCHAR(20) NOT NULL, adresse VARCHAR(100) NOT NULL, prix_total DOUBLE PRECISION NOT NULL, INDEX IDX_6EEAA67DA76ED395 (user_id), UNIQUE INDEX UNIQ_6EEAA67D7F2DEE08 (facture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE decoration (id INT AUTO_INCREMENT NOT NULL, logo_id INT NOT NULL, exemplaire_id INT NOT NULL, INDEX IDX_7649DA7F98F144A (logo_id), INDEX IDX_7649DA75843AA21 (exemplaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE demande (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, forfait_id INT NOT NULL, date_demande DATETIME NOT NULL, message LONGTEXT NOT NULL, sujet VARCHAR(100) NOT NULL, INDEX IDX_2694D7A5A76ED395 (user_id), INDEX IDX_2694D7A5906D5F2C (forfait_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE exemplaire (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_5EF83C92A76ED395 (user_id), INDEX IDX_5EF83C92F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, date_facture DATETIME NOT NULL, num_facture VARCHAR(50) NOT NULL, tva DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE fond (id INT AUTO_INCREMENT NOT NULL, couleur_fond VARCHAR(50) NOT NULL, url_fond VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE forfait (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(100) NOT NULL, prix_forfait DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE logo (id INT AUTO_INCREMENT NOT NULL, nom_logo VARCHAR(50) NOT NULL, couleur_logo SMALLINT NOT NULL, url_logo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE marquage (id INT AUTO_INCREMENT NOT NULL, typo_id INT NOT NULL, exemplaire_id INT NOT NULL, INDEX IDX_83C5D1E9AAF16CF7 (typo_id), INDEX IDX_83C5D1E95843AA21 (exemplaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, exemplaire_id INT NOT NULL, INDEX IDX_24CC0DF282EA2E54 (commande_id), INDEX IDX_24CC0DF25843AA21 (exemplaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, nom_produit VARCHAR(100) NOT NULL, descriptif LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tarif (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, seuil_quantite INT NOT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, INDEX IDX_E7189C9F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE typo (id INT AUTO_INCREMENT NOT NULL, nom_typo VARCHAR(150) NOT NULL, url_typo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE base ADD CONSTRAINT FK_C0B4FE6187EEAE74 FOREIGN KEY (fond_id) REFERENCES fond (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE base ADD CONSTRAINT FK_C0B4FE615843AA21 FOREIGN KEY (exemplaire_id) REFERENCES exemplaire (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE decoration ADD CONSTRAINT FK_7649DA7F98F144A FOREIGN KEY (logo_id) REFERENCES logo (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE decoration ADD CONSTRAINT FK_7649DA75843AA21 FOREIGN KEY (exemplaire_id) REFERENCES exemplaire (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5906D5F2C FOREIGN KEY (forfait_id) REFERENCES forfait (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exemplaire ADD CONSTRAINT FK_5EF83C92A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exemplaire ADD CONSTRAINT FK_5EF83C92F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marquage ADD CONSTRAINT FK_83C5D1E9AAF16CF7 FOREIGN KEY (typo_id) REFERENCES typo (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marquage ADD CONSTRAINT FK_83C5D1E95843AA21 FOREIGN KEY (exemplaire_id) REFERENCES exemplaire (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF282EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF25843AA21 FOREIGN KEY (exemplaire_id) REFERENCES exemplaire (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tarif ADD CONSTRAINT FK_E7189C9F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE base DROP FOREIGN KEY FK_C0B4FE6187EEAE74
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE base DROP FOREIGN KEY FK_C0B4FE615843AA21
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D7F2DEE08
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE decoration DROP FOREIGN KEY FK_7649DA7F98F144A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE decoration DROP FOREIGN KEY FK_7649DA75843AA21
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5906D5F2C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exemplaire DROP FOREIGN KEY FK_5EF83C92A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exemplaire DROP FOREIGN KEY FK_5EF83C92F347EFB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marquage DROP FOREIGN KEY FK_83C5D1E9AAF16CF7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marquage DROP FOREIGN KEY FK_83C5D1E95843AA21
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF282EA2E54
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF25843AA21
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tarif DROP FOREIGN KEY FK_E7189C9F347EFB
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE base
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE commande
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE decoration
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE demande
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE exemplaire
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE facture
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE fond
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE forfait
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE logo
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE marquage
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE panier
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE produit
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tarif
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE typo
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
