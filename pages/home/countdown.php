<?php
$page_title = "Countdown - Plateforme MMA";
include '../../includes/header.php';
?>

<section class="py-12 bg-noir">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="font-bebas text-4xl md:text-5xl text-center text-white mb-2 tracking-wide">
            COMPTE À REBOURS
        </h2>
        <p class="font-anek text-center text-gris-clair/80 mb-8">
            Temps restant avant la fin des votes
        </p>
        
        <div class="flex items-center justify-center w-full gap-6 count-down-main">
            <div class="timer flex flex-col items-center">
                <div class="bg-rouge py-6 px-4 rounded-xl shadow-lg min-w-[80px]">
                    <h3 class="countdown-element days font-bebas text-3xl text-white text-center leading-none">
                        00
                    </h3>
                </div>
                <p class="text-lg font-anek font-medium text-dore mt-3 text-center">JOURS</p>
            </div>
            
            <div class="text-3xl font-bebas text-dore">:</div>
            
            <div class="timer flex flex-col items-center">
                <div class="bg-bleu py-6 px-4 rounded-xl shadow-lg min-w-[80px]">
                    <h3 class="countdown-element hours font-bebas text-3xl text-white text-center leading-none">
                        00
                    </h3>
                </div>
                <p class="text-lg font-anek font-medium text-dore mt-3 text-center">HEURES</p>
            </div>
            
            <div class="text-3xl font-bebas text-dore">:</div>
            
            <div class="timer flex flex-col items-center">
                <div class="bg-dore py-6 px-4 rounded-xl shadow-lg min-w-[80px]">
                    <h3 class="countdown-element minutes font-bebas text-3xl text-noir text-center leading-none">
                        00
                    </h3>
                </div>
                <p class="text-lg font-anek font-medium text-dore mt-3 text-center">MINUTES</p>
            </div>
            
            <div class="text-3xl font-bebas text-dore">:</div>
            
            <div class="timer flex flex-col items-center">
                <div class="bg-gris-clair py-6 px-4 rounded-xl shadow-lg min-w-[80px] animate-pulse">
                    <h3 class="countdown-element seconds font-bebas text-3xl text-noir text-center leading-none">
                        00
                    </h3>
                </div>
                <p class="text-lg font-anek font-medium text-dore mt-3 text-center">SECONDES</p>
            </div>
        </div>

        <!-- Message d'état -->
        <div class="text-center mt-8">
            <p class="font-anek text-gris-clair/60 text-sm" id="countdown-status">
                Vote en cours - Chaque seconde compte !
            </p>
        </div>
    </div>
</section>

<!-- Script JavaScript pour le countdown -->
<script src="../../assets/js/countdown.js"></script>

<?php include '../../includes/footer.php'; ?>