<?php

/**
 * Upload d'une photo de profil pour les candidats
 * @param array $file Le fichier téléchargé ($_FILES['photo'])
 * @return string|false Le chemin relatif de la photo ou false en cas d'échec
 */
function uploadPhoto($file) {
    // Vérifier qu'un fichier a été uploadé
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    // Extensions autorisées
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($fileExtension, $allowedExtensions)) {
        return false;
    }
    
    // Taille max : 5 Mo
    if ($file['size'] > 5 * 1024 * 1024) {
        return false;
    }
    
    // Créer le dossier images/candidats s'il n'existe pas
    $uploadDir = __DIR__ . '/../images/candidats/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Générer un nom unique
    $newFileName = uniqid('candidat_', true) . '.' . $fileExtension;
    $uploadPath = $uploadDir . $newFileName;
    
    // Déplacer le fichier
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        // Retourner le chemin relatif
        return '/sae3_mma_charpentier_errebache/images/candidats/' . $newFileName;
    }
    
    return false;
}