<?php
/**
 * Section Pondération - Explication du système de vote 40/40/20
 * Détaille la répartition équitable entre public, pros et performances
 */
?>

<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        
        <!-- En-tête de section -->
        <div class="text-center mb-16">
            <h2 class="font-bebas text-6xl md:text-7xl text-noir mb-6 tracking-wide">
                SYSTÈME DE PONDÉRATION
            </h2>
            <p class="font-anek text-xl text-noir/70 max-w-3xl mx-auto">
                Notre algorithme équitable combine trois critères essentiels pour désigner le combattant de l'année
            </p>
        </div>

        <!-- Présentation des 3 critères principaux -->
        <div class="max-w-4xl mx-auto mb-16">
            <div class="bg-gradient-to-r from-gris-clair to-white p-8 rounded-2xl shadow-xl">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Critère 1: Vote Journalistes (40%) -->
                    <div class="ponderation-card opacity-0 translate-y-8 transition-all duration-1000" id="journalistes-vote">
                        <div class="text-center p-6 bg-white rounded-xl shadow-lg border-l-4 border-rouge">
                            <div class="mb-4">
                                <div class="w-20 h-20 bg-rouge rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="font-bebas text-3xl text-white">40%</span>
                                </div>
                                <h3 class="font-bebas text-2xl text-rouge tracking-wide mb-2">
                                    JOURNALISTES
                                </h3>
                            </div>
                            <p class="font-anek text-noir/80 text-base leading-relaxed">
                                L'expertise des journalistes spécialisés MMA. Leur analyse professionnelle représente 40% du score final.
                            </p>
                        </div>
                    </div>

                    <!-- Critère 2: Vote Coachs (40%) -->
                    <div class="ponderation-card opacity-0 translate-y-8 transition-all duration-1000" id="coachs-vote">
                        <div class="text-center p-6 bg-white rounded-xl shadow-lg border-l-4 border-bleu">
                            <div class="mb-4">
                                <div class="w-20 h-20 bg-bleu rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="font-bebas text-3xl text-white">40%</span>
                                </div>
                                <h3 class="font-bebas text-2xl text-bleu tracking-wide mb-2">
                                    COACHS
                                </h3>
                            </div>
                            <p class="font-anek text-noir/80 text-base leading-relaxed">
                                La vision technique des coachs professionnels contribue également à hauteur de 40% du résultat.
                            </p>
                        </div>
                    </div>

                    <!-- Critère 3: Vote Public (20%) -->
                    <div class="ponderation-card opacity-0 translate-y-8 transition-all duration-1000" id="public-vote">
                        <div class="text-center p-6 bg-white rounded-xl shadow-lg border-l-4 border-dore">
                            <div class="mb-4">
                                <div class="w-20 h-20 bg-dore rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="font-bebas text-3xl text-noir">20%</span>
                                </div>
                                <h3 class="font-bebas text-2xl text-dore tracking-wide mb-2">
                                    PUBLIC
                                </h3>
                            </div>
                            <p class="font-anek text-noir/80 text-base leading-relaxed">
                                L'avis des fans et amateurs de MMA complète également l'évaluation avec 20 % du poids décisionnel.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Explication de la transparence -->
        <div class="max-w-3xl mx-auto mt-12 text-center">
            <div class="bg-gris-clair p-6 rounded-xl">
                <p class="font-anek text-noir/70 text-base leading-relaxed">
                    <strong class="text-noir">Transparence totale :</strong> 
                    Cette répartition garantit un équilibre parfait entre l'expertise journalistique, 
                    l'expérience technique des coachs et la passion du public pour un résultat équitable et légitime.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Script d'animation des cartes de pondération -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.ponderation-card');
    let currentCard = 0;
    
    // Fonction pour afficher la carte suivante
    function showNextCard() {
        if (currentCard < cards.length) {
            cards[currentCard].classList.remove('opacity-0', 'translate-y-8');
            cards[currentCard].classList.add('opacity-100', 'translate-y-0');
            currentCard++;
            
            // Continue avec la carte suivante après un petit délai
            if (currentCard < cards.length) {
                setTimeout(showNextCard, 800);
            }
        }
    }
    
    // Observer pour déclencher l'animation quand on voit la section
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && currentCard === 0) {
                setTimeout(showNextCard, 300);
            }
        });
    }, { threshold: 0.3 });
    
    // On observe la première carte pour démarrer l'animation
    const section = document.querySelector('.ponderation-card').closest('section');
    observer.observe(section);
});
</script>