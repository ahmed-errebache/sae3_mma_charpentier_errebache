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
 * Obtenir les informations d'un code professionnel sans le marquer comme utilisé
 */
function obtenirInfoCode($code) {
    try {
        $conn = Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM code_professionnel WHERE code = :code AND utilise = 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':code' => $code]);
        $codeData = $stmt->fetch();
        
        if (!$codeData) {
            return false;
        }
        
        return [
            'code' => $codeData['code'],
            'email' => $codeData['email'],
            'prenom' => $codeData['prenom'],
            'nom' => $codeData['nom'],
            'type_professionnel' => $codeData['type_professionnel'],
            'id_college' => $codeData['id_college']
        ];
        
    } catch (PDOException $e) {
        error_log("Erreur obtenirInfoCode: " . $e->getMessage());
        return false;
    }
}

/**
 * Utiliser un code professionnel lors de l'inscription
 */
function utiliserCodeProfessionnel($code, $password, $age, $sexe, $nationalite) {
    try {
        $conn = Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM code_professionnel WHERE code = :code AND utilise = 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':code' => $code]);
        $codeData = $stmt->fetch();
        
        if (!$codeData) {
            return ['success' => false, 'error' => 'Code invalide ou déjà utilisé'];
        }
        
        // Hash du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Créer l'électeur
        $sqlElecteur = "INSERT INTO electeur (email, mot_de_passe, age, sexe, nationalite, type_professionnel, id_college) 
                        VALUES (:email, :password, :age, :sexe, :nationalite, :type_pro, :id_college)";
        $stmtElecteur = $conn->prepare($sqlElecteur);
        $resultElecteur = $stmtElecteur->execute([
            ':email' => $codeData['email'],
            ':password' => $hashedPassword,
            ':age' => $age,
            ':sexe' => $sexe,
            ':nationalite' => $nationalite,
            ':type_pro' => $codeData['type_professionnel'],
            ':id_college' => $codeData['id_college']
        ]);
        
        if (!$resultElecteur) {
            return ['success' => false, 'error' => 'Erreur lors de la création du compte'];
        }
        
        $id_electeur = $conn->lastInsertId();
        
        // Marquer le code comme utilisé
        $sqlUpdate = "UPDATE code_professionnel SET utilise = 1, date_utilisation = NOW() WHERE code = :code";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->execute([':code' => $code]);
        
        return [
            'success' => true,
            'id_electeur' => $id_electeur,
            'email' => $codeData['email']
        ];
        
    } catch (PDOException $e) {
        error_log("Erreur utiliserCodeProfessionnel: " . $e->getMessage());
        return ['success' => false, 'error' => 'Erreur technique: ' . $e->getMessage()];
    }
}
?>
