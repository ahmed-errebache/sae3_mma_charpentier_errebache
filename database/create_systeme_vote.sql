-- Script de création des tables pour le système de vote
-- Date: 2025-12-25
-- Partie: Ahmed Errebache

-- Table scrutin : gère les périodes d'élection
CREATE TABLE IF NOT EXISTS scrutin (
    id_scrutin INT AUTO_INCREMENT PRIMARY KEY,
    nom_scrutin VARCHAR(255) NOT NULL,
    annee YEAR NOT NULL,
    date_ouverture DATETIME NOT NULL,
    date_fermeture DATETIME NOT NULL,
    statut ENUM('avant', 'en_cours', 'termine') DEFAULT 'avant',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_statut (statut),
    INDEX idx_dates (date_ouverture, date_fermeture)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table vote : enregistre tous les votes
CREATE TABLE IF NOT EXISTS vote (
    id_vote INT AUTO_INCREMENT PRIMARY KEY,
    id_electeur INT NOT NULL,
    id_candidat INT NOT NULL,
    id_scrutin INT NOT NULL,
    type_electeur ENUM('public', 'coach', 'journaliste') NOT NULL,
    date_vote TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_electeur) REFERENCES electeur(id_electeur) ON DELETE CASCADE,
    FOREIGN KEY (id_candidat) REFERENCES candidat(id_candidat) ON DELETE CASCADE,
    FOREIGN KEY (id_scrutin) REFERENCES scrutin(id_scrutin) ON DELETE CASCADE,
    UNIQUE KEY unique_vote_per_scrutin (id_electeur, id_scrutin),
    INDEX idx_candidat (id_candidat),
    INDEX idx_scrutin (id_scrutin),
    INDEX idx_type_electeur (type_electeur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ajouter colonnes manquantes dans table electeur si elles n'existent pas
ALTER TABLE electeur 
    ADD COLUMN IF NOT EXISTS a_vote BOOLEAN DEFAULT FALSE,
    ADD COLUMN IF NOT EXISTS type_electeur ENUM('public', 'coach', 'journaliste') DEFAULT 'public',
    ADD COLUMN IF NOT EXISTS ip_address VARCHAR(45) NULL,
    ADD COLUMN IF NOT EXISTS code_unique VARCHAR(20) NULL UNIQUE;

-- Créer un scrutin par défaut pour 2025
INSERT INTO scrutin (nom_scrutin, annee, date_ouverture, date_fermeture, statut)
VALUES (
    'Election du combattant MMA 2025',
    2025,
    '2025-12-26 08:00:00',
    '2026-01-10 23:59:59',
    'avant'
) ON DUPLICATE KEY UPDATE id_scrutin = id_scrutin;
