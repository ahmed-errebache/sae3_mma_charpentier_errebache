<?php
/**
 * Page d'accueil de la plateforme MMA
 * Rassemble toutes les sections principales : héro, countdown, explication, etc.
 */

$page_title = "Élection MMA 2025 - Votez pour votre combattant favori";

// Inclusion du fichier de configuration

session_start();

require_once 'includes/config.php'; 

$connexion = dbconnect();

require_once 'includes/header.php';
?>

<?php if (isset($_GET['compte_supprime'])): ?>
    <div class="bg-success/10 border border-success text-success px-4 py-3 mx-auto max-w-7xl mt-24 mb-4 rounded">
        Votre compte a ete supprime avec succes. Toutes vos donnees personnelles ont ete effacees.
    </div>
<?php endif; ?>

<!-- Section héro avec présentation principale -->
<?php require_once 'pages/home/heroSection.php'; ?>

<!-- Compte à rebours des votes -->
<?php require_once 'pages/home/countdown.php'; ?>

<!-- Explication du processus de vote -->
<?php require_once 'pages/home/howItwork.php'; ?>

<!-- Détail de la pondération des votes -->
<?php require_once 'pages/home/ponderation.php'; ?>

<!-- Section contact pour l'aide -->
<?php require_once 'pages/home/contactSection.php'; ?>


<!-- Inclusion du pied de page -->
<?php require_once 'includes/footer.php'; ?>