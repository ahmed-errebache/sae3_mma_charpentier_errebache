<?php
session_start();
require_once '../includes/config.php';

// Verifier que c'est un admin
if (!isset($_SESSION['isConnected']) || $_SESSION['user_type'] !== 'administrateur') {
    header('Location: login.php');
    exit;
}

$conn = dbconnect();
$id_commentaire = $_GET['id'] ?? null;
$id_post = $_GET['post'] ?? null;

if (!$id_commentaire) {
    header('Location: posts.php');
    exit;
}

// Supprimer le commentaire
$sql = "DELETE FROM commentaire WHERE ID_commentaire = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id_commentaire]);

// Redirection
if ($id_post) {
    header('Location: voir_post.php?id=' . $id_post);
} else {
    header('Location: moderation_posts.php');
}
exit;
?>
