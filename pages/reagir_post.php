<?php
session_start();
require_once '../includes/config.php';

// Verifier que c'est un electeur connecte
if (!isset($_SESSION['isConnected']) || $_SESSION['user_type'] !== 'electeur') {
    header('Location: login.php');
    exit;
}

$conn = dbconnect();
$id_post = $_GET['id'] ?? null;
$type = $_GET['type'] ?? null;

if (!$id_post || !in_array($type, ['like', 'dislike'])) {
    header('Location: posts.php');
    exit;
}

// Recuperer l'ID de l'electeur
$sql = "SELECT ID_electeur FROM electeur WHERE email = :email";
$stmt = $conn->prepare($sql);
$stmt->execute([':email' => $_SESSION['email']]);
$electeur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$electeur) {
    header('Location: posts.php');
    exit;
}

$id_electeur = $electeur['ID_electeur'];

// Verifier si une reaction existe deja
$sql = "SELECT * FROM reaction WHERE id_post = :id_post AND id_electeur = :id_electeur";
$stmt = $conn->prepare($sql);
$stmt->execute([':id_post' => $id_post, ':id_electeur' => $id_electeur]);
$reaction_existante = $stmt->fetch(PDO::FETCH_ASSOC);

if ($reaction_existante) {
    if ($reaction_existante['type_reaction'] === $type) {
        // Supprimer la reaction si c'est la meme
        $sql = "DELETE FROM reaction WHERE ID_reaction = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $reaction_existante['ID_reaction']]);
    } else {
        // Modifier le type de reaction
        $sql = "UPDATE reaction SET type_reaction = :type WHERE ID_reaction = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':type' => $type,
            ':id' => $reaction_existante['ID_reaction']
        ]);
    }
} else {
    // Creer une nouvelle reaction
    $sql = "INSERT INTO reaction (id_post, id_electeur, type_reaction) VALUES (:id_post, :id_electeur, :type)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id_post' => $id_post,
        ':id_electeur' => $id_electeur,
        ':type' => $type
    ]);
}

// Rediriger vers la page precedente
$referer = $_SERVER['HTTP_REFERER'] ?? 'posts.php';
header('Location: ' . $referer);
exit;
?>
