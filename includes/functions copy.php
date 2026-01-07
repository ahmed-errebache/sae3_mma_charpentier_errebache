<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function genererMotDePasseProvisoire() {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $mdp = '';
    for ($i = 0; $i < 12; $i++) {
        $mdp .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $mdp;
}

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

function envoyerEmailCreationCompte($email, $mdpProvisoire, $prenom) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASSWORD;
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

function getTousCandidats() {
    $conn = dbconnect();
    $sql = "SELECT * FROM candidat ORDER BY date_creation DESC";
    $stmt = $conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCandidatById($id) {
    $conn = dbconnect();
    $sql = "SELECT * FROM candidat WHERE ID_candidat = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getCandidatByEmail($email) {
    $conn = dbconnect();
    $sql = "SELECT * FROM candidat WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

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

function changerMotDePasseCandidat($email, $mdpProvisoire, $nouveauMdp) {
    $candidat = getCandidatByEmail($email);
    
    if (!$candidat || !password_verify($mdpProvisoire, $candidat['mot_de_passe']) || $candidat['mdp_provisoire'] != 1) {
        return false;
    }
    
    return $candidat['ID_candidat'];
}

function completerProfilCandidat($id, $nouveauMdp, $surnom, $photo) {
    $conn = dbconnect();
    
    // Photo obligatoire
    if (!$photo || $photo['error'] !== 0) {
        return false;
    }
    
    $photoPath = uploadPhoto($photo);
    if (!$photoPath) {
        return false;
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
        ':surnom' => $surnom ?: null,
        ':photo' => $photoPath
    ]);
}

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

function toggleCompteActif($id) {
    $conn = dbconnect();
    $sql = "UPDATE candidat SET compte_actif = NOT compte_actif WHERE ID_candidat = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([':id' => $id]);
}

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

function calculerAge($dateNaissance) {
    if (empty($dateNaissance)) {
        return null;
    }
    $aujourdhui = new DateTime();
    $naissance = new DateTime($dateNaissance);
    return $aujourdhui->diff($naissance)->y;
}

function validerEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validerMotDePasse($password) {
    if (strlen($password) < 8) {
        return false;
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }
    if (!preg_match('/[0-9]/', $password)) {
        return false;
    }
    return true;
}

function validerDateNaissance($jour, $mois, $annee) {
    if (!checkdate($mois, $jour, $annee)) {
        return false;
    }
    $dateNaissance = "$annee-$mois-$jour";
    $age = calculerAge($dateNaissance);
    return $age >= 18 && $age <= 120;
}

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

function getScrutinActif() {
    try {
        $conn = dbconnect();
        $sql = "SELECT * FROM scrutin 
                WHERE statut = 'en_cours' 
                AND date_ouverture <= NOW() 
                AND date_fermeture >= NOW() 
                ORDER BY date_ouverture DESC 
                LIMIT 1";
        
        $stmt = $conn->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur getScrutinActif: " . $e->getMessage());
        return null;
    }
}

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
            ':id_scrutin' => $scrutin['id_scrutin']
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
        
        // Récupérer le type d'électeur
        $sqlElecteur = "SELECT type_electeur FROM electeur WHERE id_electeur = :id_electeur";
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
        $sqlCandidat = "SELECT id_candidat FROM candidat 
                        WHERE id_candidat = :id_candidat 
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
            // Enregistrer le vote
            $sqlVote = "INSERT INTO vote (id_electeur, id_candidat, id_scrutin, type_electeur) 
                        VALUES (:id_electeur, :id_candidat, :id_scrutin, :type_electeur)";
            
            $stmtVote = $conn->prepare($sqlVote);
            $stmtVote->execute([
                ':id_electeur' => $id_electeur,
                ':id_candidat' => $id_candidat,
                ':id_scrutin' => $scrutin['id_scrutin'],
                ':type_electeur' => $electeur['type_electeur']
            ]);
            
            // Mettre à jour le flag a_vote
            $sqlUpdate = "UPDATE electeur SET a_vote = 1 WHERE id_electeur = :id_electeur";
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

function getVoteElecteur($id_electeur) {
    try {
        $conn = dbconnect();
        
        // Récupérer le scrutin actif
        $scrutin = getScrutinActif();
        if (!$scrutin) {
            return null;
        }
        
        $sql = "SELECT v.*, 
                       c.nom, c.prenom, c.surnom, c.photo_profil, c.nationalite,
                       v.date_vote
                FROM vote v
                JOIN candidat c ON v.id_candidat = c.id_candidat
                WHERE v.id_electeur = :id_electeur 
                AND v.id_scrutin = :id_scrutin";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_electeur' => $id_electeur,
            ':id_scrutin' => $scrutin['id_scrutin']
        ]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        error_log("Erreur getVoteElecteur: " . $e->getMessage());
        return null;
    }
}

function genererCodeUnique() {
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $code = '';
    for ($i = 0; $i < 12; $i++) {
        $code .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $code;
}

function codeExiste($code) {
    $conn = dbconnect();
    $sql = "SELECT COUNT(*) FROM code_professionnel WHERE code = :code";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':code' => $code]);
    return $stmt->fetchColumn() > 0;
}

function creerCodeProfessionnel($email, $prenom, $nom, $type) {
    $conn = dbconnect();
    
    do {
        $code = genererCodeUnique();
    } while (codeExiste($code));
    
    $idCollege = ($type === 'journaliste') ? 2 : 3;
    
    $sql = "INSERT INTO code_professionnel (code, email, prenom, nom, type_professionnel, id_college) 
            VALUES (:code, :email, :prenom, :nom, :type, :id_college)";
    
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
        ':code' => $code,
        ':email' => $email,
        ':prenom' => $prenom,
        ':nom' => $nom,
        ':type' => $type,
        ':id_college' => $idCollege
    ]);
    
    if ($result) {
        return ['success' => true, 'code' => $code];
    }
    return ['success' => false];
}

function envoyerCodeProfessionnel($email, $prenom, $code, $type) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        $mail->setFrom(SMTP_USER, 'MMA Fighter Election');
        $mail->addAddress($email, $prenom);
        
        $mail->isHTML(true);
        $mail->Subject = 'Votre code de connexion - Election MMA';
        
        $typeFr = ($type === 'journaliste') ? 'Journaliste' : 'Coach';
        
        $templatePath = __DIR__ . '/../templates/email_code_professionnel.html';
        if (file_exists($templatePath)) {
            $template = file_get_contents($templatePath);
            $mail->Body = str_replace(
                ['{{prenom}}', '{{code}}', '{{type}}', '{{lien_connexion}}'],
                [htmlspecialchars($prenom), htmlspecialchars($code), $typeFr, BASE_URL . 'pages/login.php'],
                $template
            );
        } else {
            $mail->Body = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
            </head>
            <body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f3f4f6;">
                <div style="max-width:600px;margin:0 auto;">
                    <div style="background:#1f2937;padding:30px;text-align:center;">
                        <h1 style="color:#fff;margin:0;">MMA Fighter Election</h1>
                    </div>
                    
                    <div style="padding:40px 30px;background:#fff;">
                        <h2 style="color:#1f2937;">Bonjour ' . htmlspecialchars($prenom) . ',</h2>
                        
                        <p style="color:#4b5563;">
                            Vous avez ete enregistre en tant que <strong>' . $typeFr . '</strong> pour participer a l\'election du combattant MMA de l\'annee.
                        </p>
                        
                        <p style="color:#4b5563;">
                            Voici votre code de connexion unique :
                        </p>
                        
                        <div style="background:#f9fafb;padding:20px;border-radius:8px;margin:20px 0;text-align:center;">
                            <p style="margin:0;font-size:32px;color:#2563eb;font-weight:bold;letter-spacing:3px;">' . htmlspecialchars($code) . '</p>
                        </div>
                        
                        <p style="color:#4b5563;">
                            Ce code est a usage unique et vous permettra de voter lors de la periode de vote.
                        </p>
                        
                        <div style="text-align:center;margin:30px 0;">
                            <a href="' . BASE_URL . 'pages/login.php" style="background:#2563eb;color:#fff;padding:12px 30px;text-decoration:none;border-radius:6px;display:inline-block;">
                                Se connecter
                            </a>
                        </div>
                        
                        <p style="color:#6b7280;font-size:14px;">
                            Ce code est confidentiel. Ne le partagez avec personne.
                        </p>
                    </div>
                    
                    <div style="background:#f9fafb;padding:20px;text-align:center;">
                        <p style="color:#6b7280;font-size:12px;margin:0;">
                            MMA Fighter Election - SAE3 IUT
                        </p>
                    </div>
                </div>
            </body>
            </html>';
        }
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur envoi email: " . $mail->ErrorInfo);
        return false;
    }
}

function verifierCodeUnique($code) {
    $conn = dbconnect();
    $sql = "SELECT * FROM code_professionnel WHERE code = :code AND utilise = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':code' => $code]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function utiliserCodeProfessionnel($code, $motDePasse, $age = null, $sexe = null, $nationalite = null) {
    $conn = dbconnect();
    
    $codeInfo = verifierCodeUnique($code);
    if (!$codeInfo) {
        return ['success' => false, 'error' => 'Code invalide ou deja utilise'];
    }
    
    $mdpHash = password_hash($motDePasse, PASSWORD_DEFAULT);
    
    $sqlElecteur = "INSERT INTO electeur (email, mot_de_passe, prenom, nom, age, sexe, nationalite, code_fourni, id_college) 
                    VALUES (:email, :mdp, :prenom, :nom, :age, :sexe, :nationalite, :code, :id_college)";
    
    $stmt = $conn->prepare($sqlElecteur);
    $resultElecteur = $stmt->execute([
        ':email' => $codeInfo['email'],
        ':mdp' => $mdpHash,
        ':prenom' => $codeInfo['prenom'],
        ':nom' => $codeInfo['nom'],
        ':age' => $age,
        ':sexe' => $sexe,
        ':nationalite' => $nationalite,
        ':code' => $code,
        ':id_college' => $codeInfo['id_college']
    ]);
    
    if ($resultElecteur) {
        $sqlUpdate = "UPDATE code_professionnel 
                      SET utilise = 1, date_utilisation = NOW() 
                      WHERE ID_code = :id";
        $stmt = $conn->prepare($sqlUpdate);
        $stmt->execute([':id' => $codeInfo['ID_code']]);
        
        return ['success' => true, 'id_electeur' => $conn->lastInsertId()];
    }
    
    return ['success' => false, 'error' => 'Erreur creation compte'];
}
?>
