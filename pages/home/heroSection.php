<?php
/**
 * Section hero - Première impression de la plateforme
 * Présentation du concept principal avec les boutons d'action
 */
?>

<section class="bg-gris-clair dark:bg-noir">
    <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
        
        <!-- Contenu textuel principal -->
        <div class="mr-auto place-self-center lg:col-span-7">
            <?php if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true): ?>
                <?php
                // Récupérer le prénom de l'utilisateur connecté
                $connexion = dbconnect();
                $email = $_SESSION['email'];
                $userType = $_SESSION['user_type'];
                
                $sql = "SELECT prenom FROM $userType WHERE email = :email";
                $stmt = $connexion->prepare($sql);
                $stmt->execute([':email' => $email]);
                $userData = $stmt->fetch(PDO::FETCH_ASSOC);
                $prenom = $userData['prenom'] ?? '';
                ?>
                <p class="hero-welcome text-dore font-bebas text-2xl md:text-3xl mb-2 opacity-0 translate-y-8 transition-all duration-1000">
                    Bienvenue, <?php echo htmlspecialchars($prenom); ?>
                </p>
            <?php endif; ?>
            
            <h1 class="hero-title max-w-2xl mb-4 font-bebas text-5xl md:text-6xl xl:text-7xl text-noir dark:text-white tracking-wide leading-tight opacity-0 translate-y-8 transition-all duration-1000">
                ELECTION DU COMBATTANT MMA DE L'ANNÉE
            </h1>
            
            <p class="hero-subtitle max-w-2xl mb-6 font-anek font-normal text-noir/80 lg:mb-8 md:text-lg lg:text-xl dark:text-gris-clair/90 opacity-0 translate-y-8 transition-all duration-1000 delay-300">
                Une plateforme de vote transparente réunissant le public, les coachs et les journalistes pour élire le meilleur combattant 2025
            </p>
            
            <!-- Boutons d'action principaux -->
            <div class="hero-buttons flex flex-wrap gap-3 opacity-0 translate-y-8 transition-all duration-1000 delay-600">
                <?php if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true): ?>
                    <a href="<?php echo $base_url; ?>/pages/profil.php" class="inline-flex items-center justify-center px-6 py-3 font-anek font-medium text-center text-white rounded-lg bg-rouge hover:bg-rouge/90 focus:ring-4 focus:ring-rouge/30 transition-all duration-200 hover:scale-105">
                        Mon Profil
                        <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                <?php else: ?>
                    <a href="<?php echo $base_url; ?>/pages/login.php" class="inline-flex items-center justify-center px-6 py-3 font-anek font-medium text-center text-white rounded-lg bg-rouge hover:bg-rouge/90 focus:ring-4 focus:ring-rouge/30 transition-all duration-200 hover:scale-105">
                        Se connecter
                        <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                <?php endif; ?>
                
                <a href="#comment-ca-marche" class="inline-flex items-center justify-center px-6 py-3 font-anek font-medium text-center text-noir border-2 border-bleu rounded-lg hover:bg-bleu hover:text-white focus:ring-4 focus:ring-bleu/30 dark:text-white dark:border-dore dark:hover:bg-dore dark:hover:text-noir transition-all duration-200 hover:scale-105">
                    En savoir plus
                </a>
            </div>
        </div>
        
        <!-- Image d'illustration (cachée sur mobile) -->
        <div class="hidden lg:mt-0 lg:col-span-5 lg:flex">
            <img src="<?php echo $base_url; ?>/assets/img/hero-section.png" 
                 alt="Combattants MMA en action" 
                 class="hero-image w-full rounded-lg shadow-2xl opacity-0 translate-x-8 transition-all duration-1200 delay-900" />
        </div>
    </div>
</section>

<!-- Animation d'entrée de la hero section -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour déclencher les animations de la hero section
    function animateHeroElements() {
        const welcome = document.querySelector('.hero-welcome');
        const title = document.querySelector('.hero-title');
        const subtitle = document.querySelector('.hero-subtitle');
        const buttons = document.querySelector('.hero-buttons');
        const image = document.querySelector('.hero-image');
        
        // Animation du message de bienvenue
        if (welcome) {
            welcome.classList.remove('opacity-0', 'translate-y-8');
            welcome.classList.add('opacity-100', 'translate-y-0');
        }
        
        // Animation du titre
        if (title) {
            title.classList.remove('opacity-0', 'translate-y-8');
            title.classList.add('opacity-100', 'translate-y-0');
        }
        
        // Animation du sous-titre (après 300ms)
        if (subtitle) {
            setTimeout(() => {
                subtitle.classList.remove('opacity-0', 'translate-y-8');
                subtitle.classList.add('opacity-100', 'translate-y-0');
            }, 300);
        }
        
        // Animation des boutons (après 600ms)
        if (buttons) {
            setTimeout(() => {
                buttons.classList.remove('opacity-0', 'translate-y-8');
                buttons.classList.add('opacity-100', 'translate-y-0');
            }, 600);
        }
        
        // Animation de l'image (après 900ms)
        if (image) {
            setTimeout(() => {
                image.classList.remove('opacity-0', 'translate-x-8');
                image.classList.add('opacity-100', 'translate-x-0');
            }, 900);
        }
    }
    
    // Démarrer l'animation après un court délai
    setTimeout(animateHeroElements, 200);
});
</script>