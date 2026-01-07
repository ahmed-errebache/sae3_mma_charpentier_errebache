<?php
// Configuration de securite HTTP

// Protection XSS
header("X-XSS-Protection: 1; mode=block");

// Empecher le chargement dans une iframe
header("X-Frame-Options: DENY");

// Empecher le sniffing MIME
header("X-Content-Type-Options: nosniff");

// Politique de referrer
header("Referrer-Policy: strict-origin-when-cross-origin");

// Desactiver les informations de version PHP
header_remove("X-Powered-By");
?>
