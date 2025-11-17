<?php
/**
 * Section "Comment ça marche"
 * Explique le processus de vote en 4 étapes simples
 */
?>

<section class="py-16 bg-gris-clair" id="comment-ca-marche">
    <div class="max-w-7xl mx-auto px-4">
        
        <!-- En-tête de section -->
        <div class="text-center mb-16">
            <h2 class="font-bebas text-6xl md:text-7xl text-noir mb-6 tracking-wide">
                COMMENT ÇA MARCHE ?
            </h2>
            <p class="font-anek text-xl text-noir/70 max-w-3xl mx-auto">
                Un processus simple et transparent pour élire le combattant qui a marqué l'année 2025
            </p>        
        </div>       
        
        <!-- Étapes simplifiées -->
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Étape 1 : Connexion -->
                <div class="step-card opacity-0 translate-y-8 transition-all duration-1000" id="step-1">
                    <div class="bg-white p-8 rounded-xl shadow-lg text-center h-full">
                        <div class="w-20 h-20 bg-rouge rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bebas text-2xl text-rouge tracking-wide mb-4">
                            CONNECTEZ-VOUS
                        </h3>
                        <p class="font-anek text-noir/80 text-base leading-relaxed">
                            <span class="block mb-2"><strong>Journalistes & Coachs :</strong> Code unique reçu par email</span>
                            <span class="block"><strong>Public :</strong> Inscription unique</span>
                        </p>
                    </div>
                </div>

                <!-- Étape 2 : Votez -->
                <div class="step-card opacity-0 translate-y-8 transition-all duration-1000" id="step-2">
                    <div class="bg-white p-8 rounded-xl shadow-lg text-center h-full">
                        <div class="w-20 h-20 bg-bleu rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bebas text-2xl text-bleu tracking-wide mb-4">
                            VOTEZ
                        </h3>
                        <p class="font-anek text-noir/80 text-base leading-relaxed">
                            Découvrez les candidats avec leurs performances 2025 et choisissez le combattant qui vous a le plus marqué cette année.
                        </p>
                        <div class="mt-4 inline-flex items-center text-dore font-anek font-medium text-sm">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12,2L13.09,8.26L22,9L13.09,9.74L12,16L10.91,9.74L2,9L10.91,8.26L12,2Z"/>
                            </svg>
                            Vote unique et anonyme
                        </div>
                    </div>
                </div>

                <!-- Étape 3 : Résultats -->
                <div class="step-card opacity-0 translate-y-8 transition-all duration-1000" id="step-3">
                    <div class="bg-white p-8 rounded-xl shadow-lg text-center h-full">
                        <div class="w-20 h-20 bg-dore rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-noir" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bebas text-2xl text-dore tracking-wide mb-4">
                            RÉSULTATS
                        </h3>
                        <p class="font-anek text-noir/80 text-base leading-relaxed mb-4">
                            Calcul automatique avec pondération équilibrée entre expertise professionnelle et passion du public.
                        </p>
                        <h2 class="font-bebas text-noir font-large">
                                 COMBATTANT DE L'ANNÉE 2025
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Animation progressive et fluide -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const steps = document.querySelectorAll('.step-card');
    
    // Fonction pour déclencher l'animation au scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Animation en cascade avec délais
                steps.forEach((step, index) => {
                    setTimeout(() => {
                        step.classList.remove('opacity-0', 'translate-y-8');
                        step.classList.add('opacity-100', 'translate-y-0');
                    }, index * 300); // 300ms entre chaque carte
                });
                
                // On désactive l'observer après la première animation
                observer.unobserve(entry.target);
            }
        });
    }, { 
        threshold: 0.2 // Animation quand 20% de la section est visible
    });
    
    // Observer la section
    const section = document.getElementById('comment-ca-marche');
    if (section) {
        observer.observe(section);
    }
});
</script>