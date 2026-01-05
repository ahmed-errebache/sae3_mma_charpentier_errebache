-- Script de correction rapide pour la base de donnees existante
-- A executer si vous avez deja des donnees de test

-- 1. Reinitialiser le flag has_voted pour tous les electeurs
UPDATE `electeur` SET `has_voted` = 0;

-- 2. Vider la table vote (ATTENTION: supprime tous les votes existants)
DELETE FROM `vote`;

-- 3. Mettre a jour le scrutin de test pour qu'il soit actif aujourd'hui
UPDATE `scrutin` 
SET `date_ouverture` = CURDATE(), 
    `date_fermeture` = DATE_ADD(CURDATE(), INTERVAL 30 DAY),
    `phase` = 'preparation'
WHERE `ID_scrutin` = 1;

-- 4. Affecter tous les candidats verifies au scrutin 1 pour les tests
UPDATE `candidat` 
SET `id_scrutin` = 1 
WHERE `compte_verifie` = 1;

-- Message de confirmation
SELECT 'Base de donnees reinitialisee pour les tests. N oubliez pas de passer le scrutin en phase "vote" depuis l interface admin!' AS message;
