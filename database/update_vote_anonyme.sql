
ALTER TABLE `vote` 
DROP FOREIGN KEY `fk_vote_electeur`;

ALTER TABLE `vote` 
DROP COLUMN `id_electeur`;

