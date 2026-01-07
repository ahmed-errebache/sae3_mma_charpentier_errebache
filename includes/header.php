<?php
// Chemin de base du projet
$base_url = '/sae3_mma_charpentier_errebache';

// Récupérer les informations de l'utilisateur connecté
$user_name = '';
if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true && isset($_SESSION['email']) && isset($_SESSION['user_type'])) {
    require_once __DIR__ . '/config.php';
    $conn = dbconnect();
    $table = $_SESSION['user_type'];
    
    $sql = "SELECT prenom, nom FROM $table WHERE email = :email LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $_SESSION['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $user_name = $user['prenom'] . ' ' . $user['nom'];
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MMA Fighter Election</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo $base_url; ?>/assets/img/favicon.ico" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts pour les typographies personnalisées -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Anek+Bangla:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
    
    <!-- Configuration Tailwind personnalisée -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        // Palette colorielle 
                        'rouge': '#DE1315',
                        'bleu': '#1847C7', 
                        'dore': '#D5A845',
                        'gris-clair': '#F3F3F3',
                        'noir': '#19191E',

                        // Couleurs d'état et feedback
                        success: '#10B981',
                        warning: '#F59E0B',
                        error: '#EF4444',
                        info: '#3B82F6',
                    },
                    fontFamily: {
                        // Typographies
                        'bebas': ['Bebas Neue', 'sans-serif'],
                        'anek': ['Anek Bangla', 'sans-serif']
                    },
                    
                }
            }
        }
    </script>
</head>
<body id="top" class="bg-gris-clair">
    
    <!-- Header fixe avec taille définie -->
    <header class="fixed top-0 left-0 w-full h-20 bg-white/95 backdrop-blur-sm border-b border-gray-200 z-50 shadow-sm">
        <div class="mx-auto px-4 sm:px-6 max-w-7xl h-full">
            <nav aria-label="Global" class="items-center justify-between h-full md:justify-center relative flex">
                <div class="items-center md:absolute md:inset-y-0 md:left-0 flex flex-1">
                    <div class="items-center justify-between w-full md:w-auto flex h-full">
                        <a href="<?php echo $base_url; ?>/index.php" class="flex items-center space-x-3 h-full py-2">
                            <img alt="MMA Fighter Election Logo" src="<?php echo $base_url; ?>/images/logo.png" class="h-12 w-auto" />
                            <span class="text-lg font-bebas text-noir tracking-wide hidden sm:inline">MMA Fighter Election</span>
                        </a>
                        <div class="items-center md:hidden -mr-2 flex">
                            <button aria-expanded="false" type="button" class="mobile-menu-button p-2 inline-flex hover:text-rouge hover:bg-gris-clair focus:outline-none focus:ring-2 focus:ring-inset focus:ring-rouge bg-transparent rounded-md items-center justify-center text-noir transition-colors duration-200">
                                <span class="sr-only">Open main menu</span>
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- boutons placés au centre -->
                <div class="md:flex md:space-x-8 hidden">
                    <a href="<?php echo $base_url; ?>/index.php" class="font-medium text-noir hover:text-rouge transition-colors duration-200 py-2">Accueil</a>
                    <a href="<?php echo $base_url; ?>/pages/candidats.php" class="font-medium text-noir hover:text-bleu transition-colors duration-200 py-2">Candidats</a>
                    <a href="<?php echo $base_url; ?>/pages/posts.php" class="font-medium text-noir hover:text-dore transition-colors duration-200 py-2">Posts</a>
                    <?php if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] && $_SESSION['user_type'] === 'administrateur'): ?>
                        <a href="<?php echo $base_url; ?>/admin/generer_codes.php" class="font-medium text-noir hover:text-dore transition-colors duration-200 py-2">CodePro</a>
                        <a href="<?php echo $base_url; ?>/admin/creer_scrutin.php" class="font-medium text-noir hover:text-rouge transition-colors duration-200 py-2">Scrutins</a>
                        <a href="<?php echo $base_url; ?>/admin/resultats.php" class="font-medium text-noir hover:text-bleu transition-colors duration-200 py-2">Resultats</a>
                        <a href="<?php echo $base_url; ?>/pages/moderation_posts.php" class="font-medium text-noir hover:text-rouge transition-colors duration-200 py-2">Moderation</a>
                    <?php elseif (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] && $_SESSION['user_type'] === 'electeur'): ?>
                        <a href="<?php echo $base_url; ?>/pages/voter.php" class="font-medium text-noir hover:text-rouge transition-colors duration-200 py-2">Voter</a>
                        <a href="<?php echo $base_url; ?>/pages/contact.php" class="font-medium text-noir hover:text-dore transition-colors duration-200 py-2">Contact</a>
                    <?php elseif (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] && $_SESSION['user_type'] === 'candidat'): ?>
                        <a href="<?php echo $base_url; ?>/pages/mes_posts.php" class="font-medium text-bleu hover:text-bleu/80 transition-colors duration-200 py-2 font-bold">Mes Posts</a>
                        <a href="<?php echo $base_url; ?>/pages/contact.php" class="font-medium text-noir hover:text-dore transition-colors duration-200 py-2">Contact</a>
                    <?php else: ?>
                        <a href="<?php echo $base_url; ?>/pages/contact.php" class="font-medium text-noir hover:text-dore transition-colors duration-200 py-2">Contact</a>
                    <?php endif; ?>
                </div>
                
                <div class="md:absolute md:flex md:items-center md:justify-end md:inset-y-0 md:right-0 hidden gap-3">
                    <?php
                        // si on est connecté, afficher les boutons profil et déconnexion
                        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) {
                            echo '<span class="rounded-md inline-flex shadow-lg"><a href="'.$base_url.'/pages/profil.php" class="items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-dore border border-dore hover:bg-dore/80 hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white/30">Mon Profil</a></span>';
                            echo '<span class="rounded-md inline-flex shadow-lg"><a href="'.$base_url.'/pages/logout.php" class="items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-rouge backdrop-blur-sm inline-flex border border-white/30 hover:bg-rouge hover:border-white transition-all duration-200" title="Se déconnecter">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </a></span>';
                        }
                        // sinon le bouton "se connecter" est affiché
                        else {
                            echo '<span class="rounded-md inline-flex shadow-lg"><a href="'.$base_url.'/pages/login.php" class="items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-rouge backdrop-blur-sm inline-flex border border-white/30 hover:bg-rouge hover:border-white transition-all duration-200">Se connecter</a></span>';
                        }
                    ?>

                    <span class="ml-3 rounded-md inline-flex shadow-lg">
                        <?php 
                        // si on est connecté, le bouton "s'inscrire" n'est pas affiché
                        if (!isset($_SESSION['isConnected']) || $_SESSION['isConnected'] !== true)
                            echo '<a href="'.$base_url.'/pages/register.php" class="justify-center rounded-md py-2 px-3 bg-blanc text-noir text-sm font-medium shadow-lg inline-flex border border-blanc focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 hover:bg-gris-clair hover:scale-105 transition-all duration-200">S\'inscrire</a>';
                        ?>                        
                    </span>
                </div>
            </nav>
        </div>
        
        <!-- Menu mobile -->
        <div id="mobile-menu" class="hidden md:hidden bg-white shadow-lg border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="<?php echo $base_url; ?>/index.php" class="block px-3 py-2 rounded-md text-base font-medium text-noir hover:text-rouge hover:bg-gris-clair">Accueil</a>
                <a href="<?php echo $base_url; ?>/pages/candidats.php" class="block px-3 py-2 rounded-md text-base font-medium text-noir hover:text-bleu hover:bg-gris-clair">Candidats</a>
                <?php if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] && $_SESSION['user_type'] === 'administrateur'): ?>
                    <a href="<?php echo $base_url; ?>/admin/generer_codes.php" class="block px-3 py-2 rounded-md text-base font-medium text-noir hover:text-dore hover:bg-gris-clair">CodePro</a>
                    <a href="<?php echo $base_url; ?>/admin/creer_scrutin.php" class="block px-3 py-2 rounded-md text-base font-medium text-noir hover:text-rouge hover:bg-gris-clair">Scrutins</a>
                    <a href="<?php echo $base_url; ?>/admin/resultats.php" class="block px-3 py-2 rounded-md text-base font-medium text-noir hover:text-bleu hover:bg-gris-clair">Resultats</a>
                <?php elseif (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] && $_SESSION['user_type'] === 'electeur'): ?>
                    <a href="<?php echo $base_url; ?>/pages/voter.php" class="block px-3 py-2 rounded-md text-base font-medium text-noir hover:text-rouge hover:bg-gris-clair">Voter</a>
                    <a href="<?php echo $base_url; ?>/pages/contact.php" class="block px-3 py-2 rounded-md text-base font-medium text-noir hover:text-dore hover:bg-gris-clair">Contact</a>
                <?php else: ?>
                    <a href="<?php echo $base_url; ?>/pages/contact.php" class="block px-3 py-2 rounded-md text-base font-medium text-noir hover:text-dore hover:bg-gris-clair">Contact</a>
                <?php endif; ?>
                
                <div class="border-t border-gray-200 pt-4 pb-3">
                    <?php if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true): ?>
                        <a href="<?php echo $base_url; ?>/pages/profil.php" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-dore hover:bg-dore/80">Mon Profil</a>
                        <a href="<?php echo $base_url; ?>/pages/logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-rouge hover:bg-rouge mt-2">Se déconnecter</a>
                    <?php else: ?>
                        <a href="<?php echo $base_url; ?>/pages/login.php" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-rouge hover:bg-rouge">Se connecter</a>
                        <a href="<?php echo $base_url; ?>/pages/register.php" class="block px-3 py-2 rounded-md text-base font-medium text-noir bg-white hover:bg-gris-clair border border-gray-300 mt-2">S'inscrire</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <script>
        // Gestion du menu burger mobile
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.querySelector('.mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (menuButton && mobileMenu) {
                menuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                    const isExpanded = menuButton.getAttribute('aria-expanded') === 'true';
                    menuButton.setAttribute('aria-expanded', !isExpanded);
                });
            }
        });
    </script>

    <!-- Contenu principal avec padding-top pour compenser le header fixe -->
    <main class="pt-20 min-h-screen flex flex-col">
        <div class="relative overflow-hidden flex-1">
            <!-- Éléments décoratifs de fond -->
            <div aria-hidden="" class="h-full w-full absolute inset-y-0">
               
                    <svg class="md:translate-y-1/2 sm:translate-x-1/2 lg:translate-x-full absolute right-full transform translate-y-1/3 translate-x-1/4" width="404" height="784" fill="none" viewBox="0 0 404 784">
                        <defs>
                            <pattern id="e229dbec-10e9-49ee-8ec3-0286ca089edf" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                <rect x="0" y="0" width="4" height="4" class="text-gray-100" fill="currentColor"></rect>
                            </pattern>
                        </defs>
                        <rect width="404" height="784" fill="url(#e229dbec-10e9-49ee-8ec3-0286ca089edf)"></rect>
                    </svg>
                    <svg class="sm:-translate-x-1/2 md:-translate-x-3/4 lg:-translate-x-full absolute left-full transform -translate-y-3/4 -translate-x-1/4" width="404" height="784" fill="none" viewBox="0 0 404 784">
                        <defs>
                            <pattern id="d2a68204-c383-44b1-b99f-42ccff4e5365" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                <rect x="0" y="0" width="4" height="4" class="text-gray-100" fill="currentColor"></rect>
                            </pattern>
                        </defs>
                        <rect width="404" height="784" fill="url(#d2a68204-c383-44b1-b99f-42ccff4e5365)"></rect>
                    </svg>
                </div>
            </div>