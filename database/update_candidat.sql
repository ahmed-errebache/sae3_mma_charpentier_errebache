-- Mise à jour de la table candidat pour la gestion anonyme des comptes

-- Supprimer les anciennes colonnes si elles existent
ALTER TABLE `candidat` DROP COLUMN IF EXISTS `palmares_annee`;
ALTER TABLE `candidat` DROP COLUMN IF EXISTS `pays_origine`;
ALTER TABLE `candidat` DROP COLUMN IF EXISTS `slugon`;

-- Ajouter palmares si n'existe pas
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE table_schema = 'mma_election' AND table_name = 'candidat' AND column_name = 'palmares');
SET @query = IF(@col_exists = 0, 
    'ALTER TABLE `candidat` ADD COLUMN `palmares` TEXT DEFAULT NULL COMMENT "JSON object avec victoires, defaites, egalites, no_contest" AFTER `nationalite`', 
    'SELECT "Column palmares already exists"');
PREPARE stmt FROM @query;
EXECUTE stmt;

-- Ajouter compte_verifie si n'existe pas
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE table_schema = 'mma_election' AND table_name = 'candidat' AND column_name = 'compte_verifie');
SET @query = IF(@col_exists = 0, 
    'ALTER TABLE `candidat` ADD COLUMN `compte_verifie` TINYINT(1) DEFAULT 0 AFTER `photo_profil`', 
    'SELECT "Column compte_verifie already exists"');
PREPARE stmt FROM @query;
EXECUTE stmt;

-- Ajouter mdp_provisoire si n'existe pas
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE table_schema = 'mma_election' AND table_name = 'candidat' AND column_name = 'mdp_provisoire');
SET @query = IF(@col_exists = 0, 
    'ALTER TABLE `candidat` ADD COLUMN `mdp_provisoire` TINYINT(1) DEFAULT 1 AFTER `compte_verifie`', 
    'SELECT "Column mdp_provisoire already exists"');
PREPARE stmt FROM @query;
EXECUTE stmt;

-- Ajouter compte_actif si n'existe pas
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE table_schema = 'mma_election' AND table_name = 'candidat' AND column_name = 'compte_actif');
SET @query = IF(@col_exists = 0, 
    'ALTER TABLE `candidat` ADD COLUMN `compte_actif` TINYINT(1) DEFAULT 1 AFTER `mdp_provisoire`', 
    'SELECT "Column compte_actif already exists"');
PREPARE stmt FROM @query;
EXECUTE stmt;

-- Ajouter date_creation si n'existe pas
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE table_schema = 'mma_election' AND table_name = 'candidat' AND column_name = 'date_creation');
SET @query = IF(@col_exists = 0, 
    'ALTER TABLE `candidat` ADD COLUMN `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP AFTER `compte_actif`', 
    'SELECT "Column date_creation already exists"');
PREPARE stmt FROM @query;
EXECUTE stmt;
