-- Procédure stockée pour enregistrer un vote
-- Cette procédure garantit l'intégrité transactionnelle lors de l'enregistrement d'un vote
-- Elle insère le vote et met à jour le statut has_voted de l'électeur

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_enregistrer_vote$$

CREATE PROCEDURE sp_enregistrer_vote(
    IN p_id_electeur INT,
    IN p_id_candidat INT,
    IN p_id_scrutin INT,
    IN p_age INT,
    IN p_sexe VARCHAR(20),
    IN p_nationalite VARCHAR(50),
    IN p_id_college INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Erreur lors de l\'enregistrement du vote';
    END;

    START TRANSACTION;
    
    -- Insérer le vote
    INSERT INTO vote (
        id_electeur, 
        date, 
        date_vote, 
        age, 
        sexe, 
        nationalite, 
        id_college, 
        id_candidat, 
        id_scrutin
    ) VALUES (
        p_id_electeur,
        CURDATE(),
        NOW(),
        p_age,
        p_sexe,
        p_nationalite,
        p_id_college,
        p_id_candidat,
        p_id_scrutin
    );
    
    -- Mettre à jour le statut has_voted
    UPDATE electeur 
    SET has_voted = 1 
    WHERE ID_electeur = p_id_electeur;
    
    COMMIT;
END$$

DELIMITER ;
