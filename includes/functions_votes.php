<?php
/**
 * Fonctions liées aux votes
 * Principe SOLID : Single Responsibility (gestion votes uniquement)
 */

require_once __DIR__ . '/Database.php';

/**
 * Récupère le scrutin actif
 */
function getScrutinActif() {
    try {
        $conn = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM scrutin 
                WHERE phase = 'vote' 
                AND date_ouverture <= CURDATE() 
                AND date_fermeture >= CURDATE() 
                ORDER BY date_ouverture DESC 
                LIMIT 1";
        
        $stmt = $conn->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur getScrutinActif: " . $e->getMessage());
        return null;
    }
}

/**
 * Vérifie si un électeur peut voter
 */
function peutVoter($id_electeur) {
    try {
        $conn = Database::getInstance()->getConnection();
        
        // Vérifier scrutin actif
        $scrutin = getScrutinActif();
        if (!$scrutin) {
            return [
                'peut_voter' => false,
                'message' => 'Aucun scrutin n\'est actuellement ouvert.',
                'scrutin' => null
            ];
        }
        
        // Vérifier si l'électeur a déjà voté via la colonne has_voted
        $sql = "SELECT has_voted FROM electeur WHERE ID_electeur = :id_electeur";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_electeur' => $id_electeur]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && $result['has_voted'] == 1) {
            return [
                'peut_voter' => false,
                'message' => 'Vous avez déjà voté pour ce scrutin.',
                'scrutin' => $scrutin
            ];
        }
        
        return [
            'peut_voter' => true,
            'message' => 'Vous pouvez voter.',
            'scrutin' => $scrutin
        ];
        
    } catch (PDOException $e) {
        error_log("Erreur peutVoter: " . $e->getMessage());
        return [
            'peut_voter' => false,
            'message' => 'Une erreur est survenue lors de la vérification.',
            'scrutin' => null
        ];
    }
}

/**
 * Enregistre un vote dans la base de données de manière anonyme
 */
function enregistrerVote($id_electeur, $id_candidat) {
    try {
        $conn = Database::getInstance()->getConnection();
        
        // Vérifier si l'électeur peut voter
        $verification = peutVoter($id_electeur);
        if (!$verification['peut_voter']) {
            return [
                'success' => false,
                'message' => $verification['message']
            ];
        }
        
        $scrutin = $verification['scrutin'];
        
        // Récupérer les infos de l'électeur
        $sqlElecteur = "SELECT e.*, c.type as type_college 
                        FROM electeur e 
                        LEFT JOIN college c ON e.id_college = c.ID_college 
                        WHERE e.ID_electeur = :id_electeur";
        $stmtElecteur = $conn->prepare($sqlElecteur);
        $stmtElecteur->execute([':id_electeur' => $id_electeur]);
        $electeur = $stmtElecteur->fetch(PDO::FETCH_ASSOC);
        
        if (!$electeur) {
            return [
                'success' => false,
                'message' => 'Électeur introuvable.'
            ];
        }
        
        // Vérifier que le candidat existe et est vérifié
        $sqlCandidat = "SELECT ID_candidat FROM candidat 
                        WHERE ID_candidat = :id_candidat 
                        AND compte_verifie = 1";
        $stmtCandidat = $conn->prepare($sqlCandidat);
        $stmtCandidat->execute([':id_candidat' => $id_candidat]);
        
        if (!$stmtCandidat->fetch()) {
            return [
                'success' => false,
                'message' => 'Candidat invalide.'
            ];
        }
        
        // Démarrer une transaction
        $conn->beginTransaction();
        
        try {
            // Insérer le vote SANS id_electeur (anonymat garanti)
            $sqlVote = "INSERT INTO vote (date, date_vote, age, sexe, nationalite, id_college, id_candidat, id_scrutin)
                        VALUES (CURDATE(), NOW(), :age, :sexe, :nationalite, :id_college, :id_candidat, :id_scrutin)";
            
            $stmtVote = $conn->prepare($sqlVote);
            $stmtVote->execute([
                ':age' => $electeur['age'],
                ':sexe' => $electeur['sexe'],
                ':nationalite' => $electeur['nationalite'],
                ':id_college' => $electeur['id_college'],
                ':id_candidat' => $id_candidat,
                ':id_scrutin' => $scrutin['ID_scrutin']
            ]);
            
            // Marquer l'électeur comme ayant voté
            $sqlUpdate = "UPDATE electeur SET has_voted = 1 WHERE ID_electeur = :id_electeur";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->execute([':id_electeur' => $id_electeur]);
            
            // Valider la transaction
            $conn->commit();
            
            return [
                'success' => true,
                'message' => 'Votre vote a été enregistré avec succès !'
            ];
            
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $conn->rollBack();
            error_log("Erreur insertion vote: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'enregistrement du vote.'
            ];
        }
        
    } catch (PDOException $e) {
        error_log("Erreur enregistrerVote: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Une erreur est survenue. Veuillez réessayer.'
        ];
    }
}

/**
 * Vérifie si un électeur a déjà voté (pour affichage)
 * Note : On ne peut plus récupérer le vote spécifique car il est anonyme
 */
function aVote($id_electeur) {
    try {
        $conn = Database::getInstance()->getConnection();
        
        $sql = "SELECT has_voted FROM electeur WHERE ID_electeur = :id_electeur";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_electeur' => $id_electeur]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['has_voted'] == 1;
        
    } catch (PDOException $e) {
        error_log("Erreur aVote: " . $e->getMessage());
        return false;
    }
}

/**
 * Calcule les résultats d'un scrutin avec pondération par collège
 */
function calculerResultatsScrutin($id_scrutin) {
    try {
        $conn = Database::getInstance()->getConnection();
        
        $sql = "SELECT 
                    c.ID_candidat,
                    c.nom,
                    c.prenom,
                    c.surnom,
                    c.photo_profil,
                    c.nationalite,
                    COUNT(v.ID_vote) as nb_votes_total,
                    SUM(CASE WHEN co.type = 'public' THEN 1 ELSE 0 END) as nb_votes_public,
                    SUM(CASE WHEN co.type = 'journaliste' THEN 1 ELSE 0 END) as nb_votes_journaliste,
                    SUM(CASE WHEN co.type = 'coach' THEN 1 ELSE 0 END) as nb_votes_coach
                FROM candidat c
                LEFT JOIN vote v ON c.ID_candidat = v.id_candidat AND v.id_scrutin = :id_scrutin
                LEFT JOIN college co ON v.id_college = co.ID_college
                WHERE c.compte_verifie = 1
                GROUP BY c.ID_candidat
                ORDER BY nb_votes_total DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_scrutin' => $id_scrutin]);
        $resultats_bruts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $sqlPoids = "SELECT type, poids FROM college";
        $stmtPoids = $conn->query($sqlPoids);
        $poids = [];
        while ($row = $stmtPoids->fetch(PDO::FETCH_ASSOC)) {
            $poids[$row['type']] = (float)$row['poids'];
        }
        
        $sqlTotalVotes = "SELECT 
                            co.type,
                            COUNT(v.ID_vote) as total
                          FROM vote v
                          JOIN college co ON v.id_college = co.ID_college
                          WHERE v.id_scrutin = :id_scrutin
                          GROUP BY co.type";
        $stmtTotal = $conn->prepare($sqlTotalVotes);
        $stmtTotal->execute([':id_scrutin' => $id_scrutin]);
        $totaux_colleges = ['public' => 0, 'journaliste' => 0, 'coach' => 0];
        while ($row = $stmtTotal->fetch(PDO::FETCH_ASSOC)) {
            $totaux_colleges[$row['type']] = (int)$row['total'];
        }
        
        $resultats = [];
        foreach ($resultats_bruts as $candidat) {
            $score_pondere = 0;
            
            $pourcentage_public = $totaux_colleges['public'] > 0 ? 
                ($candidat['nb_votes_public'] / $totaux_colleges['public']) * 100 : 0;
            $pourcentage_journaliste = $totaux_colleges['journaliste'] > 0 ? 
                ($candidat['nb_votes_journaliste'] / $totaux_colleges['journaliste']) * 100 : 0;
            $pourcentage_coach = $totaux_colleges['coach'] > 0 ? 
                ($candidat['nb_votes_coach'] / $totaux_colleges['coach']) * 100 : 0;
            
            $score_pondere = 
                ($pourcentage_public * $poids['public']) +
                ($pourcentage_journaliste * $poids['journaliste']) +
                ($pourcentage_coach * $poids['coach']);
            
            $resultats[] = [
                'candidat' => $candidat,
                'nb_votes_public' => (int)$candidat['nb_votes_public'],
                'nb_votes_journaliste' => (int)$candidat['nb_votes_journaliste'],
                'nb_votes_coach' => (int)$candidat['nb_votes_coach'],
                'nb_votes_total' => (int)$candidat['nb_votes_total'],
                'pourcentage_public' => round($pourcentage_public, 2),
                'pourcentage_journaliste' => round($pourcentage_journaliste, 2),
                'pourcentage_coach' => round($pourcentage_coach, 2),
                'score_pondere' => round($score_pondere, 2)
            ];
        }
        
        usort($resultats, function($a, $b) {
            return $b['score_pondere'] <=> $a['score_pondere'];
        });
        
        return [
            'resultats' => $resultats,
            'totaux_colleges' => $totaux_colleges,
            'poids' => $poids
        ];
        
    } catch (PDOException $e) {
        error_log("Erreur calculerResultatsScrutin: " . $e->getMessage());
        return null;
    }
}
?>
