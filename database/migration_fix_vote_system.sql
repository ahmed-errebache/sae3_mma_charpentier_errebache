-- Migration pour corriger le systeme de vote
-- Date: 05/01/2026
-- Auteur: Ahmed Errebache

-- Ajouter les colonnes manquantes dans la table vote
ALTER TABLE `vote` 
ADD COLUMN `id_electeur` int(11) DEFAULT NULL AFTER `ID_vote`,
ADD COLUMN `date_vote` datetime DEFAULT CURRENT_TIMESTAMP AFTER `date`;

-- Ajouter l'index pour id_electeur
ALTER TABLE `vote`
ADD KEY `fk_vote_electeur` (`id_electeur`);

-- Ajouter la contrainte de cle etrangere
ALTER TABLE `vote`
ADD CONSTRAINT `fk_vote_electeur` FOREIGN KEY (`id_electeur`) REFERENCES `electeur` (`ID_electeur`) ON DELETE CASCADE;

-- Mettre a jour la contrainte existante pour id_candidat si elle n'existe pas
-- ALTER TABLE `vote`
-- ADD CONSTRAINT `fk_vote_candidat` FOREIGN KEY (`id_candidat`) REFERENCES `candidat` (`ID_candidat`) ON DELETE CASCADE;

-- Mettre a jour la contrainte existante pour id_scrutin si elle n'existe pas
-- ALTER TABLE `vote`
-- ADD CONSTRAINT `fk_vote_scrutin` FOREIGN KEY (`id_scrutin`) REFERENCES `scrutin` (`ID_scrutin`) ON DELETE CASCADE;

-- Reinitialiser le flag has_voted pour tous les electeurs (pour les tests)
-- UPDATE `electeur` SET `has_voted` = 0;

-- Vider la table vote pour les tests (ATTENTION: supprime tous les votes!)
-- DELETE FROM `vote`;
