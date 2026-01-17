-- Script SQL pour les modifications de la base de données
-- Exécuter ces commandes dans phpMyAdmin

-- 1. Modifier le champ mdp_provisoire dans la table candidat
-- Ce champ doit être un booléen (TINYINT(1)) pour indiquer si le mot de passe est provisoire

ALTER TABLE `candidat` 
MODIFY COLUMN `mdp_provisoire` TINYINT(1) DEFAULT 1 COMMENT 'Indique si le mot de passe est provisoire (1) ou définitif (0)';

-- 2. Ajouter un index sur les champs fréquemment utilisés pour améliorer les performances

-- Index sur le champ email pour les recherches rapides
CREATE INDEX idx_candidat_email ON candidat(email);
CREATE INDEX idx_electeur_email ON electeur(email);
CREATE INDEX idx_administrateur_email ON administrateur(email);

-- Index sur les codes professionnels
CREATE INDEX idx_code_utilise ON code_professionnel(code, utilise);

-- Index sur les votes
CREATE INDEX idx_vote_scrutin ON vote(id_scrutin);
CREATE INDEX idx_vote_electeur ON vote(id_electeur, id_scrutin);

-- Index sur le scrutin actif
CREATE INDEX idx_scrutin_dates ON scrutin(date_ouverture, date_fermeture, phase);

-- 3. Assurer que les tables utilisent bien utf8mb4 pour supporter tous les caractères

ALTER TABLE administrateur CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE candidat CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE electeur CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE code_professionnel CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE college CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE scrutin CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE vote CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE post CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE commentaire CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE reaction CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE media CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 4. Ajouter des contraintes de clés étrangères si elles n'existent pas déjà
-- (Vérifier avant d'exécuter si ces contraintes existent)

-- Contrainte pour vote -> electeur
ALTER TABLE vote 
ADD CONSTRAINT fk_vote_electeur 
FOREIGN KEY (id_electeur) REFERENCES electeur(ID_electeur) 
ON DELETE CASCADE;

-- Contrainte pour vote -> candidat
ALTER TABLE vote 
ADD CONSTRAINT fk_vote_candidat 
FOREIGN KEY (id_candidat) REFERENCES candidat(ID_candidat) 
ON DELETE CASCADE;

-- Contrainte pour vote -> scrutin
ALTER TABLE vote 
ADD CONSTRAINT fk_vote_scrutin 
FOREIGN KEY (id_scrutin) REFERENCES scrutin(ID_scrutin) 
ON DELETE CASCADE;

-- Contrainte pour vote -> college
ALTER TABLE vote 
ADD CONSTRAINT fk_vote_college 
FOREIGN KEY (id_college) REFERENCES college(ID_college) 
ON DELETE CASCADE;

-- Contrainte pour code_professionnel -> college
ALTER TABLE code_professionnel 
ADD CONSTRAINT fk_code_college 
FOREIGN KEY (id_college) REFERENCES college(ID_college) 
ON DELETE CASCADE;

-- Note: Si vous obtenez une erreur disant que la contrainte existe déjà, 
-- c'est normal, cela signifie qu'elle a déjà été créée précédemment.
