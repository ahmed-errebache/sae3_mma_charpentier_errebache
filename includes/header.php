<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme MMA</title>
      <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>    <!-- Google Fonts pour les typographies personnalisées -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Anek+Bangla:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Configuration Tailwind personnalisée -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        // Palette colorielle personnalisée
                        'rouge': '#DE1315',
                        'bleu': '#1847C7', 
                        'dore': '#D5A845',
                        'gris-clair': '#F3F3F3',
                        'noir': '#19191E',
                        
                        // Couleurs primaires existantes
                        primary: {
                            "50":"#fef2f2",
                            "100":"#fee2e2",
                            "200":"#fecaca",
                            "300":"#fca5a5",
                            "400":"#f87171",
                            "500":"#ef4444",
                            "600":"#dc2626",
                            "700":"#b91c1c",
                            "800":"#991b1b",
                            "900":"#7f1d1d",
                            "950":"#450a0a"
                        },

                        // Couleurs d'état et feedback
                        success: '#10B981',
                        warning: '#F59E0B',
                        error: '#EF4444',
                        info: '#3B82F6',
                    },
                    fontFamily: {
                        // Typographies personnalisées
                        'bebas': ['Bebas Neue', 'sans-serif'],
                        'anek': ['Anek Bangla', 'sans-serif']
                    },
                    fontSize: {
                        // Tailles personnalisées si nécessaire
                        'display': ['4rem', '1'],
                        'hero': ['3rem', '1.1'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
<h1>hello from header</h1>