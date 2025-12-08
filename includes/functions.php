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
    
    $palmaresJson = json_encode($data['palmares']);
    
    $sql = "INSERT INTO candidat (email, mot_de_passe, nom, prenom, nationalite, pays_origine, palmares, mdp_provisoire, compte_verifie, compte_actif) 
            VALUES (:email, :mdp, :nom, :prenom, :nationalite, :pays_origine, :palmares, 1, 0, 1)";
    
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
        ':email' => $data['email'],
        ':mdp' => $mdpHash,
        ':nom' => $data['nom'],
        ':prenom' => $data['prenom'],
        ':nationalite' => $data['nationalite'],
        ':pays_origine' => $data['pays_origine'],
        ':palmares' => $palmaresJson
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
    
    $palmaresJson = json_encode($data['palmares']);
    
    $sql = "UPDATE candidat 
            SET nom = :nom, prenom = :prenom, nationalite = :nationalite, 
                pays_origine = :pays_origine, palmares = :palmares 
            WHERE ID_candidat = :id";
    
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':nom' => $data['nom'],
        ':prenom' => $data['prenom'],
        ':nationalite' => $data['nationalite'],
        ':pays_origine' => $data['pays_origine'],
        ':palmares' => $palmaresJson
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
function completerProfilCandidat($id, $nouveauMdp, $surnom, $slugon, $photo) {
    $conn = dbconnect();
    
    $photoPath = null;
    if ($photo && $photo['error'] === 0) {
        $photoPath = uploadPhoto($photo);
    }
    
    $mdpHash = password_hash($nouveauMdp, PASSWORD_DEFAULT);
    
    $sql = "UPDATE candidat 
            SET mot_de_passe = :mdp, surnom = :surnom, slugon = :slugon, 
                photo_profil = :photo, mdp_provisoire = 0, compte_verifie = 1 
            WHERE ID_candidat = :id";
    
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':mdp' => $mdpHash,
        ':surnom' => $surnom,
        ':slugon' => $slugon,
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
?>
