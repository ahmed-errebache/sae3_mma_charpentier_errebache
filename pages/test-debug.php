<?php
// Script de test pour debugger
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

echo "<h1>Test de debug</h1>";

// Test 1: Scrutin actif
echo "<h2>1. Test du scrutin actif</h2>";
$scrutin = getScrutinActif();
echo "<pre>";
var_dump($scrutin);
echo "</pre>";

// Test 2: Info utilisateur connecte
if (isset($_SESSION['email']) && isset($_SESSION['user_type'])) {
    echo "<h2>2. Test de l'utilisateur connecte</h2>";
    echo "Email: " . $_SESSION['email'] . "<br>";
    echo "Type: " . $_SESSION['user_type'] . "<br>";
    
    $conn = dbconnect();
    $table = $_SESSION['user_type'];
    $sql = "SELECT * FROM $table WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $_SESSION['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h3>Donnees de l'utilisateur:</h3>";
    echo "<pre>";
    var_dump($user);
    echo "</pre>";
} else {
    echo "<h2>2. Pas d'utilisateur connecte</h2>";
    echo "Veuillez vous connecter d'abord.";
}
?>
