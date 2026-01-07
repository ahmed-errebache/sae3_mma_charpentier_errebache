<?php
// Script de test pour debugger
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

echo "<h1>Test de debug</h1>";
echo "<style>body{font-family:Arial;padding:20px;} pre{background:#f4f4f4;padding:10px;border-radius:5px;}</style>";

// Test 1: Scrutin actif
echo "<h2>1. Test du scrutin actif</h2>";
$scrutin = getScrutinActif();
if ($scrutin) {
    echo "<p style='color:green'>✓ Scrutin actif trouve</p>";
    echo "<pre>";
    print_r($scrutin);
    echo "</pre>";
    
    // Tester la conversion de date
    $dateStr = $scrutin['date_fermeture'] . ' 23:59:59';
    $timestamp = strtotime($dateStr);
    $dateFin = date('F d, Y H:i:s', $timestamp);
    echo "<p><strong>Date formatee pour JS:</strong> $dateFin</p>";
    echo "<p><strong>Timestamp:</strong> $timestamp</p>";
    
    $now = time();
    $diff = $timestamp - $now;
    $jours = floor($diff / (60 * 60 * 24));
    echo "<p><strong>Temps restant:</strong> $jours jours</p>";
} else {
    echo "<p style='color:red'>✗ Aucun scrutin actif</p>";
}

// Test 2: Info utilisateur connecte
if (isset($_SESSION['email']) && isset($_SESSION['user_type'])) {
    echo "<h2>2. Test de l'utilisateur connecte</h2>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($_SESSION['email']) . "</p>";
    echo "<p><strong>Type:</strong> " . htmlspecialchars($_SESSION['user_type']) . "</p>";
    
    $conn = dbconnect();
    $table = $_SESSION['user_type'];
    $sql = "SELECT * FROM $table WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $_SESSION['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<h3>Donnees de l'utilisateur:</h3>";
        echo "<pre>";
        print_r($user);
        echo "</pre>";
        
        // Test specifique des champs problematiques
        echo "<h3>Test des champs age, sexe, nationalite:</h3>";
        echo "<p><strong>age:</strong> ";
        if (isset($user['age'])) {
            echo "Existe - Valeur: " . var_export($user['age'], true);
            echo " - Type: " . gettype($user['age']);
            echo " - empty(): " . (empty($user['age']) ? 'true' : 'false');
            echo " - is_null(): " . (is_null($user['age']) ? 'true' : 'false');
        } else {
            echo "N'existe pas dans le tableau";
        }
        echo "</p>";
        
        echo "<p><strong>sexe:</strong> ";
        if (isset($user['sexe'])) {
            echo "Existe - Valeur: " . var_export($user['sexe'], true);
            echo " - Type: " . gettype($user['sexe']);
            echo " - empty(): " . (empty($user['sexe']) ? 'true' : 'false');
        } else {
            echo "N'existe pas";
        }
        echo "</p>";
        
        echo "<p><strong>nationalite:</strong> ";
        if (isset($user['nationalite'])) {
            echo "Existe - Valeur: " . var_export($user['nationalite'], true);
            echo " - Type: " . gettype($user['nationalite']);
            echo " - empty(): " . (empty($user['nationalite']) ? 'true' : 'false');
        } else {
            echo "N'existe pas";
        }
        echo "</p>";
    } else {
        echo "<p style='color:red'>✗ Utilisateur non trouve dans la base</p>";
    }
} else {
    echo "<h2>2. Pas d'utilisateur connecte</h2>";
    echo "<p style='color:orange'>Veuillez vous connecter d'abord.</p>";
    echo "<a href='login.php'>Se connecter</a>";
}

echo "<hr><p><a href='../index.php'>Retour a l'accueil</a></p>";
?>
