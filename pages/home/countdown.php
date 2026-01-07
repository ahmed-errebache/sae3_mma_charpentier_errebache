<?php
/**
 * Section countdown - Affiche le temps restant pour voter
 */

// Recuperation du scrutin actif
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

$conn = dbconnect();
$scrutinActif = getScrutinActif();

// Date par defaut
$dateFin = 'December 31, 2025 23:59:59';

if ($scrutinActif && isset($scrutinActif['date_fermeture'])) {
    // La date dans la base est au format DATE (YYYY-MM-DD) sans heure
    // On ajoute 23:59:59 pour avoir la fin de la journee
    $dateStr = $scrutinActif['date_fermeture'] . ' 23:59:59';
    $timestamp = strtotime($dateStr);
    $dateFin = date('F d, Y H:i:s', $timestamp);
}
?>

<section class="py-8 md:py-12 bg-noir">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        
        <!-- En-tête de la section -->
        <h2 class="font-bebas text-2xl sm:text-3xl md:text-4xl lg:text-5xl text-center text-white mb-6 md:mb-8 tracking-wide leading-tight">
            TEMPS RESTANT AVANT LA FIN DES VOTES
        </h2>
        
        <!-- Affichage du chronomètre responsive -->
        <div class="flex items-center justify-center w-full gap-2 sm:gap-4 md:gap-6 count-down-main">
            
            <!-- Jours -->
            <div class="timer flex flex-col items-center">
                <div class="bg-rouge py-3 sm:py-4 md:py-6 px-2 sm:px-3 md:px-4 rounded-lg md:rounded-xl shadow-lg min-w-[60px] sm:min-w-[70px] md:min-w-[80px] lg:min-w-[90px]">
                    <h3 class="countdown-element days font-bebas text-xl sm:text-2xl md:text-3xl lg:text-4xl text-white text-center leading-none">
                        00
                    </h3>
                </div>
                <p class="text-xs sm:text-sm md:text-lg font-anek font-medium text-dore mt-2 md:mt-3 text-center">JOURS</p>
            </div>
            
            <div class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bebas text-dore">:</div>
            
            <!-- Heures -->
            <div class="timer flex flex-col items-center">
                <div class="bg-bleu py-3 sm:py-4 md:py-6 px-2 sm:px-3 md:px-4 rounded-lg md:rounded-xl shadow-lg min-w-[60px] sm:min-w-[70px] md:min-w-[80px] lg:min-w-[90px]">
                    <h3 class="countdown-element hours font-bebas text-xl sm:text-2xl md:text-3xl lg:text-4xl text-white text-center leading-none">
                        00
                    </h3>
                </div>
                <p class="text-xs sm:text-sm md:text-lg font-anek font-medium text-dore mt-2 md:mt-3 text-center">HEURES</p>
            </div>
            
            <div class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bebas text-dore">:</div>
            
            <!-- Minutes -->
            <div class="timer flex flex-col items-center">
                <div class="bg-dore py-3 sm:py-4 md:py-6 px-2 sm:px-3 md:px-4 rounded-lg md:rounded-xl shadow-lg min-w-[60px] sm:min-w-[70px] md:min-w-[80px] lg:min-w-[90px]">
                    <h3 class="countdown-element minutes font-bebas text-xl sm:text-2xl md:text-3xl lg:text-4xl text-noir text-center leading-none">
                        00
                    </h3>
                </div>
                <p class="text-xs sm:text-sm md:text-lg font-anek font-medium text-dore mt-2 md:mt-3 text-center">MINUTES</p>
            </div>
            
            <div class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bebas text-dore">:</div>
            
            <!-- Secondes avec animation -->
            <div class="timer flex flex-col items-center">
                <div class="bg-gris-clair py-3 sm:py-4 md:py-6 px-2 sm:px-3 md:px-4 rounded-lg md:rounded-xl shadow-lg min-w-[60px] sm:min-w-[70px] md:min-w-[80px] lg:min-w-[90px] animate-pulse">
                    <h3 class="countdown-element seconds font-bebas text-xl sm:text-2xl md:text-3xl lg:text-4xl text-noir text-center leading-none">
                        00
                    </h3>
                </div>
                <p class="text-xs sm:text-sm md:text-lg font-anek font-medium text-dore mt-2 md:mt-3 text-center">SECONDES</p>
            </div>
        </div>
    </div>
</section>

<!-- Chargement du script pour le compteur -->
<script>
    // Passer la date de fin du PHP au JavaScript
    const countdownDate = "<?php echo $dateFin; ?>";
</script>
<script src="<?php echo $base_url; ?>/assets/js/countdown.js"></script>