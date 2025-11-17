<?php
/**
 * Section countdown - Affiche le temps restant pour voter
 * Utilise un script JS pour mettre à jour en temps réel
 */
?>

<section class="py-12 bg-noir">
    <div class="max-w-4xl mx-auto px-4">
        
        <!-- En-tête de la section -->
        <h2 class="font-bebas text-4xl md:text-5xl text-center text-white mb-2 tracking-wide">
            TEMPS RESTANT AVANT LA FIN DES VOTES
        </h2>
        
        <!-- Affichage du chronomètre -->
        <div class="flex items-center justify-center w-full gap-6 count-down-main">
            
            <!-- Jours -->
            <div class="timer flex flex-col items-center">
                <div class="bg-rouge py-6 px-4 rounded-xl shadow-lg min-w-[80px]">
                    <h3 class="countdown-element days font-bebas text-3xl text-white text-center leading-none">
                        00
                    </h3>
                </div>
                <p class="text-lg font-anek font-medium text-dore mt-3 text-center">JOURS</p>
            </div>
            
            <div class="text-3xl font-bebas text-dore">:</div>
            
            <!-- Heures -->
            <div class="timer flex flex-col items-center">
                <div class="bg-bleu py-6 px-4 rounded-xl shadow-lg min-w-[80px]">
                    <h3 class="countdown-element hours font-bebas text-3xl text-white text-center leading-none">
                        00
                    </h3>
                </div>
                <p class="text-lg font-anek font-medium text-dore mt-3 text-center">HEURES</p>
            </div>
            
            <div class="text-3xl font-bebas text-dore">:</div>
            
            <!-- Minutes -->
            <div class="timer flex flex-col items-center">
                <div class="bg-dore py-6 px-4 rounded-xl shadow-lg min-w-[80px]">
                    <h3 class="countdown-element minutes font-bebas text-3xl text-noir text-center leading-none">
                        00
                    </h3>
                </div>
                <p class="text-lg font-anek font-medium text-dore mt-3 text-center">MINUTES</p>
            </div>
            
            <div class="text-3xl font-bebas text-dore">:</div>
            
            <!-- Secondes avec animation -->
            <div class="timer flex flex-col items-center">
                <div class="bg-gris-clair py-6 px-4 rounded-xl shadow-lg min-w-[80px] animate-pulse">
                    <h3 class="countdown-element seconds font-bebas text-3xl text-noir text-center leading-none">
                        00
                    </h3>
                </div>
                <p class="text-lg font-anek font-medium text-dore mt-3 text-center">SECONDES</p>
            </div>
        </div>

        <!-- Message d'information -->
        <div class="text-center mt-8">
            <h4 class="font-anek text-gris-clair" id="countdown-status">
                Vote en cours - Chaque seconde compte !
            </h4>
        </div>
    </div>
</section>

<!-- Chargement du script pour le compteur -->
<script src="../../assets/js/countdown.js"></script>