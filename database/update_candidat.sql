-- Mise à jour de la table candidat pour la gestion anonyme des comptes

ALTER TABLE `candidat`
ADD COLUMN `pays_origine` VARCHAR(100) DEFAULT NULL AFTER `nationalite`,
ADD COLUMN `palmares` TEXT DEFAULT NULL COMMENT 'JSON array du palmarès 2025' AFTER `pays_origine`,
ADD COLUMN `slugon` VARCHAR(255) DEFAULT NULL AFTER `surnom`,
ADD COLUMN `compte_verifie` TINYINT(1) DEFAULT 0 AFTER `photo_profil`,
ADD COLUMN `mdp_provisoire` TINYINT(1) DEFAULT 1 AFTER `compte_verifie`,
ADD COLUMN `compte_actif` TINYINT(1) DEFAULT 1 AFTER `mdp_provisoire`,
ADD COLUMN `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP AFTER `compte_actif`;

-- Supprimer l'ancienne colonne palmares_annee si elle existe
ALTER TABLE `candidat` DROP COLUMN IF EXISTS `palmares_annee`;
