<?php
/**
 * Fonctions utilitaires générales
 * Principe SOLID : Single Responsibility (utilitaires généraux)
 */

require_once __DIR__ . '/Database.php';

/**
 * Calculer l'age à partir d'une date de naissance
 */
function calculerAge($dateNaissance) {
    if (empty($dateNaissance)) {
        return null;
    }
    try {
        $naissance = new DateTime($dateNaissance);
        $aujourdhui = new DateTime();
        $age = $aujourdhui->diff($naissance)->y;
        return $age;
    } catch (Exception $e) {
        error_log("Erreur calcul age: " . $e->getMessage());
        return null;
    }
}

/**
 * Liste de tous les pays du monde
 */
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
 * Supprimer le compte utilisateur
 */
function supprimerCompte($email, $userType) {
    try {
        $conn = Database::getInstance()->getConnection();
        
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

/**
 * Valider un mot de passe
 * Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre
 */
function validerMotDePasse($motDePasse) {
    if (strlen($motDePasse) < 8) {
        return false;
    }
    
    if (!preg_match('/[A-Z]/', $motDePasse)) {
        return false;
    }
    
    if (!preg_match('/[0-9]/', $motDePasse)) {
        return false;
    }
    
    return true;
}
?>
