<?php
/**
 * Page d'accueil de la plateforme MMA
 * Rassemble toutes les sections principales : héro, countdown, explication, etc.
 */

$page_title = "Élection MMA 2025 - Votez pour votre combattant favori";
include '../../includes/header.php';
?>

<!-- Section héro avec présentation principale -->
<?php include 'heroSection.php'; ?>

<!-- Compte à rebours des votes -->
<?php include 'countdown.php'; ?>

<!-- Explication du processus de vote -->
<?php include 'howItwork.php'; ?>

<!-- Détail de la pondération des votes -->
<?php include 'ponderation.php'; ?>

<!-- Section contact pour l'aide -->
<?php include 'contactSection.php'; ?>

<?php include '../../includes/footer.php'; ?>