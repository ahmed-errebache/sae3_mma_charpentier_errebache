<?php
/**
 * Page d'accueil de la plateforme MMA
 * Rassemble toutes les sections principales : héro, countdown, explication, etc.
 */

$page_title = "Élection MMA 2025 - Votez pour votre combattant favori";
// Inclusion du fichier de configuration
include 'includes/header.php';
?>

<!-- Section héro avec présentation principale -->
<?php include 'pages/home/heroSection.php'; ?>

<!-- Compte à rebours des votes -->
<?php include 'pages/home/countdown.php'; ?>

<!-- Explication du processus de vote -->
<?php include 'pages/home/howItwork.php'; ?>

<!-- Détail de la pondération des votes -->
<?php include 'pages/home/ponderation.php'; ?>

<!-- Section contact pour l'aide -->
<?php include 'pages/home/contactSection.php'; ?>


<!-- Inclusion du pied de page -->
<?php include 'includes/footer.php'; ?>