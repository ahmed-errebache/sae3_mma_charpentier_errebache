<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Générer un mot de passe provisoire aléatoire
function genererMotDePasseProvisoire() {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $mdp = '';
    for ($i = 0; $i < 12; $i++) {
        $mdp .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $mdp;
}

// Créer un candidat et envoyer l'email
function creerCandidat($data) {
    $conn = dbconnect();
    
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
}

// Envoyer email de création de compte
function envoyerEmailCreationCompte($email, $mdpProvisoire, $prenom) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ahmed.errebache@gmail.com'; // À configurer dans READMEMAILER.md
        $mail->Password = 'cnij ihjw zmbw qxyh'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        $mail->setFrom('votre-email@gmail.com', 'MMA Fighter Election');
        $mail->addAddress($email, $prenom);
        
        $mail->isHTML(true);
        $mail->Subject = 'Création de votre compte candidat';
        
        $lienFinalisation = BASE_URL . 'pages/candidat.php?action=finaliser';
        
        $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
        </head>
        <body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f3f4f6;">
            <div style="max-width:600px;margin:0 auto;">
                <div style="background:#1f2937;padding:30px;text-align:center;">
                    <h1 style="color:#fff;margin:0;font-size:24px;">MMA Fighter Election</h1>
                </div>
                
                <div style="padding:40px 30px;background:#fff;">
                    <h2 style="color:#1f2937;margin:0 0 20px 0;">Bonjour ' . htmlspecialchars($prenom) . ',</h2>
                    
                    <p style="color:#4b5563;line-height:1.6;margin:0 0 20px 0;">
                        Votre compte candidat a été créé. Voici vos identifiants de connexion :
                    </p>
                    
                    <div style="background:#f9fafb;padding:20px;border-radius:8px;margin:20px 0;">
                        <p style="margin:0 0 10px 0;color:#1f2937;"><strong>Email :</strong> ' . htmlspecialchars($email) . '</p>
                        <p style="margin:0;color:#1f2937;"><strong>Mot de passe provisoire :</strong> ' . htmlspecialchars($mdpProvisoire) . '</p>
                    </div>
                    
                    <p style="color:#4b5563;line-height:1.6;margin:20px 0;">
                        Cliquez sur le bouton ci-dessous pour finaliser votre compte.
                    </p>
                    
                    <div style="text-align:center;margin:30px 0;">
                        <a href="' . $lienFinalisation . '" style="background:#1f2937;color:#fff;padding:14px 32px;text-decoration:none;border-radius:6px;display:inline-block;font-weight:500;">
                            Finaliser mon compte
                        </a>
                    </div>
                </div>
                
                <div style="background:#f3f4f6;padding:20px;text-align:center;color:#6b7280;font-size:14px;">
                    <p style="margin:0;">© 2025 MMA Fighter Election</p>
                </div>
            </div>
        </body>
        </html>';
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur envoi email: {$mail->ErrorInfo}");
        return false;
    }
}

// Récupérer tous les candidats
function getTousCandidats() {
    $conn = dbconnect();
    $sql = "SELECT * FROM candidat ORDER BY date_creation DESC";
    $stmt = $conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupérer un candidat par ID
function getCandidatById($id) {
    $conn = dbconnect();
    $sql = "SELECT * FROM candidat WHERE ID_candidat = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupérer un candidat par email
function getCandidatByEmail($email) {
    $conn = dbconnect();
    $sql = "SELECT * FROM candidat WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Modifier les informations du candidat (admin uniquement)
function modifierCandidatAdmin($id, $data) {
    $conn = dbconnect();
    
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
}

// Finaliser le compte candidat - Étape 1: Changer mot de passe
function changerMotDePasseCandidat($email, $mdpProvisoire, $nouveauMdp) {
    $candidat = getCandidatByEmail($email);
    
    if (!$candidat || !password_verify($mdpProvisoire, $candidat['mot_de_passe']) || $candidat['mdp_provisoire'] != 1) {
        return false;
    }
    
    return $candidat['ID_candidat'];
}

// Finaliser le compte candidat - Étape 2: Compléter profil
function completerProfilCandidat($id, $nouveauMdp, $surnom, $photo) {
    $conn = dbconnect();
    
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
}

// Upload photo de profil
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

// Activer/désactiver un compte
function toggleCompteActif($id) {
    $conn = dbconnect();
    $sql = "UPDATE candidat SET compte_actif = NOT compte_actif WHERE ID_candidat = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([':id' => $id]);
}

// Supprimer un candidat
function supprimerCandidat($id) {
    $conn = dbconnect();
    
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
}

// Liste de tous les pays du monde
function getListePays() {
    return [
        "Afghanistan", "Afrique du Sud", "Albanie", "Algérie", "Allemagne", "Andorre", "Angola", "Antigua-et-Barbuda",
        "Arabie saoudite", "Argentine", "Arménie", "Australie", "Autriche", "Azerbaïdjan", "Bahamas", "Bahreïn",
        "Bangladesh", "Barbade", "Belgique", "Belize", "Bénin", "Bhoutan", "Biélorussie", "Birmanie", "Bolivie",
        "Bosnie-Herzégovine", "Botswana", "Brésil", "Brunei", "Bulgarie", "Burkina Faso", "Burundi", "Cambodge",
        "Cameroun", "Canada", "Cap-Vert", "Centrafrique", "Chili", "Chine", "Chypre", "Colombie", "Comores",
        "Congo-Brazzaville", "Congo-Kinshasa", "Corée du Nord", "Corée du Sud", "Costa Rica", "Côte d'Ivoire", "Croatie",
        "Cuba", "Danemark", "Djibouti", "Dominique", "Égypte", "Émirats arabes unis", "Équateur", "Érythrée", "Espagne",
        "Estonie", "Eswatini", "États-Unis", "Éthiopie", "Fidji", "Finlande", "France", "Gabon", "Gambie", "Géorgie",
        "Ghana", "Grèce", "Grenade", "Guatemala", "Guinée", "Guinée-Bissau", "Guinée équatoriale", "Guyana", "Haïti",
        "Honduras", "Hongrie", "Inde", "Indonésie", "Irak", "Iran", "Irlande", "Islande", "Israël", "Italie", "Jamaïque",
        "Japon", "Jordanie", "Kazakhstan", "Kenya", "Kirghizistan", "Kiribati", "Kosovo", "Koweït", "Laos", "Lesotho",
        "Lettonie", "Liban", "Liberia", "Libye", "Liechtenstein", "Lituanie", "Luxembourg", "Macédoine du Nord", "Madagascar",
        "Malaisie", "Malawi", "Maldives", "Mali", "Malte", "Maroc", "Marshall", "Maurice", "Mauritanie", "Mexique",
        "Micronésie", "Moldavie", "Monaco", "Mongolie", "Monténégro", "Mozambique", "Namibie", "Nauru", "Népal", "Nicaragua",
        "Niger", "Nigeria", "Norvège", "Nouvelle-Zélande", "Oman", "Ouganda", "Ouzbékistan", "Pakistan", "Palaos", "Palestine",
        "Panama", "Papouasie-Nouvelle-Guinée", "Paraguay", "Pays-Bas", "Pérou", "Philippines", "Pologne", "Portugal", "Qatar",
        "République dominicaine", "République tchèque", "Roumanie", "Royaume-Uni", "Russie", "Rwanda", "Saint-Christophe-et-Niévès",
        "Sainte-Lucie", "Saint-Marin", "Saint-Vincent-et-les-Grenadines", "Salomon", "Salvador", "Samoa", "São Tomé-et-Príncipe",
        "Sénégal", "Serbie", "Seychelles", "Sierra Leone", "Singapour", "Slovaquie", "Slovénie", "Somalie", "Soudan",
        "Soudan du Sud", "Sri Lanka", "Suède", "Suisse", "Suriname", "Syrie", "Tadjikistan", "Tanzanie", "Tchad", "Thaïlande",
        "Timor oriental", "Togo", "Tonga", "Trinité-et-Tobago", "Tunisie", "Turkménistan", "Turquie", "Tuvalu", "Ukraine",
        "Uruguay", "Vanuatu", "Vatican", "Venezuela", "Viêt Nam", "Yémen", "Zambie", "Zimbabwe"
    ];
}

/**
 * Récupère le scrutin actif
 * @return array|null Le scrutin en cours ou null
 */
function getScrutinActif() {
    try {
        $conn = dbconnect();
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
 * @param int $id_electeur L'ID de l'électeur
 * @return array ['peut_voter' => bool, 'message' => string, 'scrutin' => array|null]
 */
function peutVoter($id_electeur) {
    try {
        $conn = dbconnect();
        
        // Vérifier scrutin actif
        $scrutin = getScrutinActif();
        if (!$scrutin) {
            return [
                'peut_voter' => false,
                'message' => 'Aucun scrutin n\'est actuellement ouvert.',
                'scrutin' => null
            ];
        }
        
        // Vérifier si l'électeur a déjà voté pour ce scrutin
        $sql = "SELECT COUNT(*) as nb_votes 
                FROM vote 
                WHERE id_electeur = :id_electeur 
                AND id_scrutin = :id_scrutin";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_electeur' => $id_electeur,
            ':id_scrutin' => $scrutin['ID_scrutin']
        ]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['nb_votes'] > 0) {
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
 * Enregistre un vote dans la base de données
 * @param int $id_electeur L'ID de l'électeur
 * @param int $id_candidat L'ID du candidat choisi
 * @return array ['success' => bool, 'message' => string]
 */
function enregistrerVote($id_electeur, $id_candidat) {
    try {
        $conn = dbconnect();
        
        // Vérifier si l'électeur peut voter
        $verification = peutVoter($id_electeur);
        if (!$verification['peut_voter']) {
            return [
                'success' => false,
                'message' => $verification['message']
            ];
        }
        
        $scrutin = $verification['scrutin'];
        
        // Récupérer les infos de l'électeur avec son collège
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
        
        // Transaction pour garantir l'intégrité
        $conn->beginTransaction();
        
        try {
            // Enregistrer le vote avec les données de l'électeur
            $sqlVote = "INSERT INTO vote (id_electeur, date, date_vote, age, sexe, nationalite, id_college, id_candidat, id_scrutin) 
                        VALUES (:id_electeur, CURDATE(), NOW(), :age, :sexe, :nationalite, :id_college, :id_candidat, :id_scrutin)";
            
            $stmtVote = $conn->prepare($sqlVote);
            $stmtVote->execute([
                ':id_electeur' => $id_electeur,
                ':age' => $electeur['age'],
                ':sexe' => $electeur['sexe'],
                ':nationalite' => $electeur['nationalite'],
                ':id_college' => $electeur['id_college'],
                ':id_candidat' => $id_candidat,
                ':id_scrutin' => $scrutin['ID_scrutin']
            ]);
            
            // Mettre à jour le flag has_voted
            $sqlUpdate = "UPDATE electeur SET has_voted = 1 WHERE ID_electeur = :id_electeur";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->execute([':id_electeur' => $id_electeur]);
            
            $conn->commit();
            
            return [
                'success' => true,
                'message' => 'Votre vote a été enregistré avec succès !'
            ];
            
        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Erreur transaction vote: " . $e->getMessage());
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
 * Récupère le vote d'un électeur pour le scrutin actif
 * @param int $id_electeur L'ID de l'électeur
 * @return array|null Les informations du vote ou null si aucun vote
 */
function getVoteElecteur($id_electeur) {
    try {
        $conn = dbconnect();
        
        $scrutin = getScrutinActif();
        if (!$scrutin) {
            return null;
        }
        
        $sql = "SELECT v.*, 
                       c.nom, c.prenom, c.surnom, c.photo_profil, c.nationalite,
                       v.date_vote
                FROM vote v
                JOIN candidat c ON v.id_candidat = c.ID_candidat
                WHERE v.id_electeur = :id_electeur 
                AND v.id_scrutin = :id_scrutin";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_electeur' => $id_electeur,
            ':id_scrutin' => $scrutin['ID_scrutin']
        ]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        error_log("Erreur getVoteElecteur: " . $e->getMessage());
        return null;
    }
}

function calculerResultatsScrutin($id_scrutin) {
    try {
        $conn = dbconnect();
        
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
        $totaux_colleges = [];
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

// Supprimer le compte utilisateur
function supprimerCompte($email, $userType) {
    try {
        $conn = dbconnect();
        
        // Verification du type d'utilisateur
        if (!in_array($userType, ['electeur', 'candidat', 'administrateur'])) {
            return ['success' => false, 'message' => 'Type utilisateur invalide'];
        }
        
        // Supprimer le compte
        $sql = "DELETE FROM $userType WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([':email' => $email]);
        
        if ($result) {
            return ['success' => true, 'message' => 'Compte supprime avec succes'];
        } else {
            return ['success' => false, 'message' => 'Erreur lors de la suppression'];
        }
        
    } catch (PDOException $e) {
        error_log("Erreur supprimerCompte: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erreur technique'];
    }
}
?>
