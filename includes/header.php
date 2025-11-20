<?php
// Chemin de base du projet (à ajuster selon votre configuration)
$base_url = '/sae3_mma_charpentier_errebache';
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>    <!-- Google Fonts pour les typographies personnalisées -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Anek+Bangla:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <title>MMA Fighter Election</title>    <!-- CSS personnalisé -->
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
<body id="top" class="bg-gray-50 min-h-screen flex flex-col">
    
    <div class="relative overflow-hidden flex-1">
    <div aria-hidden="" class="h-full w-full absolute inset-y-0">
        <div class="h-full relative">
        <svg class="md:translate-y-1/2 sm:translate-x-1/2 lg:translate-x-full absolute right-full transform translate-y-1/3 translate-x-1/4" width="404" height="784" fill="none" viewBox="0 0 404 784" id="Windframe_ZsQf8hgtu">
            <defs>
            <pattern id="e229dbec-10e9-49ee-8ec3-0286ca089edf" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                <rect x="0" y="0" width="4" height="4" class="text-gray-100" fill="currentColor"></rect>
            </pattern>
            </defs>
            <rect width="404" height="784" fill="url(#e229dbec-10e9-49ee-8ec3-0286ca089edf)"></rect>
        </svg>
        <svg class="sm:-translate-x-1/2 md:-translate-x-3/4 lg:-translate-x-full absolute left-full transform -translate-y-3/4 -translate-x-1/4" width="404" height="784" fill="none" viewBox="0 0 404 784" id="Windframe_aX8SoiSk1">
            <defs>
            <pattern id="d2a68204-c383-44b1-b99f-42ccff4e5365" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                <rect x="0" y="0" width="4" height="4" class="text-gray-100" fill="currentColor"></rect>
            </pattern>
            </defs>
            <rect width="404" height="784" fill="url(#d2a68204-c383-44b1-b99f-42ccff4e5365)"></rect>
        </svg>
        </div>
    </div>
    <div class="pt-6 pb-16 sm:pb-24 bg-radial relative from-violet-300 via-transparent to-transparent">
        <div class="mx-auto px-4 sm:px-6 max-w-7xl">
        <nav aria-label="Global" class="items-center justify-between sm:h-10 md:justify-center relative flex">
            <div class="items-center md:absolute md:inset-y-0 md:left-0 flex flex-1">
            <div class="items-center justify-between w-full md:w-auto flex">                <a href="<?php echo $base_url; ?>/index.php" class="flex items-center space-x-3">
                    <img alt="MMA Fighter Election Logo" src="<?php echo $base_url; ?>/images/logo.png" class="h-16 w-auto sm:h-20" />
                    <span class="text-xl font-bebas text-noir tracking-wide">MMA Fighter Election</span>
                </a>
                <div class="items-center md:hidden -mr-2 flex">
                <button aria-expanded="false" type="button" class="mobile-menu-button p-2 inline-flex hover:text-rouge hover:bg-gris-clair focus:outline-none focus:ring-2 focus:ring-inset focus:ring-rouge bg-white rounded-md items-center justify-center text-noir transition-colors duration-200">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="" id="Windframe_0HJrazxGX">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                </div>
            </div>
            </div>            <div class="md:flex md:space-x-10 hidden">
            <a href="<?php echo $base_url; ?>/index.php" class="font-medium text-noir hover:text-rouge transition-colors duration-200">Accueil</a>
            <a href="<?php echo $base_url; ?>/pages/candidats.php" class="font-medium text-noir hover:text-bleu transition-colors duration-200">Candidats</a>
            <a href="<?php echo $base_url; ?>/pages/contact.php" class="font-medium text-noir hover:text-dore transition-colors duration-200">Contact</a>
            </div>
            <div class="md:absolute md:flex md:items-center md:justify-end md:inset-y-0 md:right-0 hidden">            <?php
                if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true)
                    echo '<span class="rounded-md inline-flex shadow"><a href="'.$base_url.'/pages/profil.php" class="items-center px-4 py-2 text-base font-medium rounded-md text-bleu bg-white inline-flex border border-bleu hover:bg-bleu hover:text-white transition-all duration-200">Profil</a></span>';
                else
                    echo '<span class="rounded-md inline-flex shadow"><a href="'.$base_url.'/pages/login.php" class="items-center px-4 py-2 text-base font-medium rounded-md text-rouge bg-white inline-flex border border-rouge hover:bg-rouge hover:text-white transition-all duration-200">Se connecter</a></span>';
            ?>

            <span class="ml-3 rounded-md inline-flex shadow"><a href="<?php echo $base_url; ?>/pages/register.php" class="justify-center rounded-md py-2 px-4 bg-dore text-noir text-sm font-medium shadow-sm inline-flex border border-dore focus:outline-none focus:ring-2 focus:ring-dore focus:ring-offset-2 hover:bg-dore/90 transition-all duration-200">S'inscrire</a></span>
            </div>
        </nav>
        </div>
