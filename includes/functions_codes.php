<?php
/**
 * Fonctions liées aux codes professionnels
 * Principe SOLID : Single Responsibility (gestion codes uniquement)
 */

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/functions_email.php';

/**
 * Vérifier si un code professionnel existe et est valide
 */
function verifierCodeUnique($code) {
    try {
        $conn = Database::getInstance()->getConnection();
        $sql = "SELECT COUNT(*) as count FROM code_professionnel WHERE code = :code";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':code' => $code]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    } catch (PDOException $e) {
        error_log("Erreur verifierCodeUnique: " . $e->getMessage());
        return false;
    }
}

/**
 * Créer un code professionnel
 */
function creerCodeProfessionnel($email, $prenom, $nom, $type_professionnel, $id_college) {
    try {
        $conn = Database::getInstance()->getConnection();
        
        // Générer un code unique
        do {
            $code = strtoupper(bin2hex(random_bytes(4)));
        } while (verifierCodeUnique($code));
        
        $sql = "INSERT INTO code_professionnel (code, email, prenom, nom, type_professionnel, id_college, date_generation, utilise) 
                VALUES (:code, :email, :prenom, :nom, :type, :id_college, NOW(), 0)";
        
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            ':code' => $code,
            ':email' => $email,
            ':prenom' => $prenom,
            ':nom' => $nom,
            ':type' => $type_professionnel,
            ':id_college' => $id_college
        ]);
        
        if ($result) {
            return ['success' => true, 'code' => $code];
        }
        
        return ['success' => false, 'error' => 'Erreur création code'];
    } catch (PDOException $e) {
        error_log("Erreur creerCodeProfessionnel: " . $e->getMessage());
        return ['success' => false, 'error' => 'Erreur technique'];
    }
}

/**
 * Utiliser un code professionnel lors de l'inscription
 */
function utiliserCodeProfessionnel($code) {
    try {
        $conn = Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM code_professionnel WHERE code = :code AND utilise = 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':code' => $code]);
        $codeData = $stmt->fetch();
        
        if (!$codeData) {
            return ['success' => false, 'message' => 'Code invalide ou déjà utilisé'];
        }
        
        // Marquer le code comme utilisé
        $sqlUpdate = "UPDATE code_professionnel SET utilise = 1, date_utilisation = NOW() WHERE code = :code";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->execute([':code' => $code]);
        
        return [
            'success' => true,
            'data' => [
                'email' => $codeData['email'],
                'prenom' => $codeData['prenom'],
                'nom' => $codeData['nom'],
                'type_professionnel' => $codeData['type_professionnel'],
                'id_college' => $codeData['id_college']
            ]
        ];
        
    } catch (PDOException $e) {
        error_log("Erreur utiliserCodeProfessionnel: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erreur technique'];
    }
}
?>
