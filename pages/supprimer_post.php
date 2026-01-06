<?php
session_start();
require_once '../includes/config.php';

// Verifier que c'est un candidat ou un admin
if (!isset($_SESSION['isConnected']) || !in_array($_SESSION['user_type'], ['candidat', 'administrateur'])) {
    header('Location: login.php');
    exit;
}

$conn = dbconnect();
$id_post = $_GET['id'] ?? null;

if (!$id_post) {
    header('Location: posts.php');
    exit;
}

// Si c'est un candidat, verifier que c'est son post
if ($_SESSION['user_type'] === 'candidat') {
    $sql = "SELECT ID_candidat FROM candidat WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $_SESSION['email']]);
    $candidat = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $sql = "SELECT id_candidat FROM post WHERE ID_post = :id_post";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_post' => $id_post]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$post || $post['id_candidat'] != $candidat['ID_candidat']) {
        header('Location: mes_posts.php');
        exit;
    }
}

// Recuperer le chemin du media pour le supprimer
$sql = "SELECT chemin_media FROM post WHERE ID_post = :id_post";
$stmt = $conn->prepare($sql);
$stmt->execute([':id_post' => $id_post]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if ($post && file_exists('../' . $post['chemin_media'])) {
    unlink('../' . $post['chemin_media']);
}

// Supprimer le post de la base
$sql = "DELETE FROM post WHERE ID_post = :id_post";
$stmt = $conn->prepare($sql);
$stmt->execute([':id_post' => $id_post]);

// Redirection
if ($_SESSION['user_type'] === 'candidat') {
    header('Location: mes_posts.php');
} else {
    header('Location: moderation_posts.php');
}
exit;
?>
