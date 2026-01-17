<?php
/**
 * Fonctions liées aux candidats
 * Principe SOLID : Single Responsibility (gestion candidats uniquement)
 */

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/functions_email.php';

/**
 * Créer un candidat et envoyer l'email
 */
function creerCandidat($data) {
    try {
        $conn = Database::getInstance()->getConnection();
        
        $mdpProvisoire = genererMotDePasseProvisoire();
        $mdpHash = password_hash($mdpProvisoire, PASSWORD_DEFAULT);
        
        // Créer le palmarès au format JSON
        $palmares = json_encode([
            'victoires' => (int)$data['victoires'],
            'defaites' => (int)$data['defaites'],
            'egalites' => (int)$data['egalites'],
            'no_contest' => (int)$data['no_contest']
        ]);
        
        $sql = "INSERT INTO candidat (email, mot_de_passe, nom, prenom, nationalite, palmares, mdp_provisoire, compte_verifie, compte_actif) 
                VALUES (:email, :mdp, :nom, :prenom, :nationalite, :palmares, 1, 0, 1)";
        
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            ':email' => $data['email'],
            ':mdp' => $mdpHash,
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':nationalite' => $data['nationalite'],
            ':palmares' => $palmares
        ]);
        
        if ($result) {
            $idCandidat = $conn->lastInsertId();
            
            if (envoyerEmailCreationCompte($data['email'], $mdpProvisoire, $data['prenom'])) {
                return ['success' => true, 'id' => $idCandidat];
            } else {
                return ['success' => false, 'error' => 'Erreur envoi email'];
            }
        }
        
        return ['success' => false, 'error' => 'Erreur création'];
    } catch (PDOException $e) {
        error_log("Erreur creerCandidat: " . $e->getMessage());
        return ['success' => false, 'error' => 'Erreur technique'];
    }
}

/**
 * Récupérer tous les candidats
 */
function getTousCandidats() {
    try {
        $conn = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM candidat ORDER BY date_creation DESC";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur getTousCandidats: " . $e->getMessage());
        return [];
    }
}

/**
 * Récupérer un candidat par ID
 */
function getCandidatById($id) {
    try {
        $conn = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM candidat WHERE ID_candidat = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur getCandidatById: " . $e->getMessage());
        return null;
    }
}

/**
 * Récupérer un candidat par email
 */
function getCandidatByEmail($email) {
    try {
        $conn = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM candidat WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur getCandidatByEmail: " . $e->getMessage());
        return null;
    }
}

/**
 * Modifier les informations du candidat (admin uniquement)
 */
function modifierCandidatAdmin($id, $data) {
    try {
        $conn = Database::getInstance()->getConnection();
        
        // Créer le palmarès au format JSON
        $palmares = json_encode([
            'victoires' => (int)$data['victoires'],
            'defaites' => (int)$data['defaites'],
            'egalites' => (int)$data['egalites'],
            'no_contest' => (int)$data['no_contest']
        ]);
        
        $sql = "UPDATE candidat 
                SET nom = :nom, prenom = :prenom, nationalite = :nationalite, 
                    palmares = :palmares 
                WHERE id_candidat = :id";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':nationalite' => $data['nationalite'],
            ':palmares' => $palmares
        ]);
    } catch (PDOException $e) {
        error_log("Erreur modifierCandidatAdmin: " . $e->getMessage());
        return false;
    }
}

/**
 * Finaliser le compte candidat - Étape 1: Changer mot de passe
 */
function changerMotDePasseCandidat($email, $mdpProvisoire, $nouveauMdp) {
    try {
        $candidat = getCandidatByEmail($email);
        
        if (!$candidat || !password_verify($mdpProvisoire, $candidat['mot_de_passe']) || $candidat['mdp_provisoire'] != 1) {
            return false;
        }
        
        return $candidat['ID_candidat'];
    } catch (Exception $e) {
        error_log("Erreur changerMotDePasseCandidat: " . $e->getMessage());
        return false;
    }
}

/**
 * Finaliser le compte candidat - Étape 2: Compléter profil
 */
function completerProfilCandidat($id, $nouveauMdp, $surnom, $photo) {
    try {
        $conn = Database::getInstance()->getConnection();
        
        $photoPath = null;
        if ($photo && $photo['error'] === 0) {
            $photoPath = uploadPhoto($photo);
        }
        
        $mdpHash = password_hash($nouveauMdp, PASSWORD_DEFAULT);
        
        $sql = "UPDATE candidat 
                SET mot_de_passe = :mdp, surnom = :surnom, 
                    photo_profil = :photo, mdp_provisoire = 0, compte_verifie = 1 
                WHERE ID_candidat = :id";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':mdp' => $mdpHash,
            ':surnom' => $surnom,
            ':photo' => $photoPath
        ]);
    } catch (PDOException $e) {
        error_log("Erreur completerProfilCandidat: " . $e->getMessage());
        return false;
    }
}

/**
 * Upload photo de profil
 */
function uploadPhoto($file) {
    $uploadDir = __DIR__ . '/../images/candidats/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (!in_array($extension, $allowedExtensions)) {
        return null;
    }
    
    $nomFichier = uniqid() . '.' . $extension;
    $cheminComplet = $uploadDir . $nomFichier;
    
    if (move_uploaded_file($file['tmp_name'], $cheminComplet)) {
        return 'images/candidats/' . $nomFichier;
    }
    
    return null;
}

/**
 * Activer/désactiver un compte
 */
function toggleCompteActif($id) {
    try {
        $conn = Database::getInstance()->getConnection();
        $sql = "UPDATE candidat SET compte_actif = NOT compte_actif WHERE ID_candidat = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    } catch (PDOException $e) {
        error_log("Erreur toggleCompteActif: " . $e->getMessage());
        return false;
    }
}

/**
 * Supprimer un candidat
 */
function supprimerCandidat($id) {
    try {
        $conn = Database::getInstance()->getConnection();
        
        $candidat = getCandidatById($id);
        if ($candidat && $candidat['photo_profil']) {
            $photoPath = __DIR__ . '/../' . $candidat['photo_profil'];
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }
        
        $sql = "DELETE FROM candidat WHERE ID_candidat = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    } catch (PDOException $e) {
        error_log("Erreur supprimerCandidat: " . $e->getMessage());
        return false;
    }
}

/**
 * Générer un mot de passe provisoire aléatoire
 */
function genererMotDePasseProvisoire() {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $mdp = '';
    $max = strlen($chars) - 1;
    for ($i = 0; $i < 12; $i++) {
        $mdp .= $chars[random_int(0, $max)];
    }
    return $mdp;
}
?>
