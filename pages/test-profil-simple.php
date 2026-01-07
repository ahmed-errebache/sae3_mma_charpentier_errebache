<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['email'])) {
    die("Pas connecte. <a href='login.php'>Se connecter</a>");
}

$conn = dbconnect();
$email = $_SESSION['email'];
$userType = $_SESSION['user_type'];

$sql = "SELECT * FROM $userType WHERE email = :email";
$stmt = $conn->prepare($sql);
$stmt->execute([':email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<h1>Test Profil Simple</h1>";
echo "<p>Email session: $email</p>";
echo "<p>Type: $userType</p>";
echo "<h2>Donnees brutes:</h2>";
echo "<pre>";
print_r($user);
echo "</pre>";

echo "<h2>Test !empty():</h2>";
echo "<p>age: " . var_export(!empty($user['age']), true) . " (valeur: " . var_export($user['age'], true) . ")</p>";
echo "<p>sexe: " . var_export(!empty($user['sexe']), true) . " (valeur: " . var_export($user['sexe'], true) . ")</p>";
echo "<p>nationalite: " . var_export(!empty($user['nationalite']), true) . " (valeur: " . var_export($user['nationalite'], true) . ")</p>";

echo "<hr><p><a href='profil.php'>Voir profil normal</a> | <a href='../index.php'>Accueil</a></p>";
?>
