
ALTER TABLE `electeur` 
ADD COLUMN `type_professionnel` ENUM('journaliste', 'coach') DEFAULT NULL AFTER `nationalite`;

