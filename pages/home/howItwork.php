<?php
$page_title = "Comment ça marche ? - Plateforme MMA";
include '../../includes/header.php';
?>

<section class="py-16 bg-gris-clair">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Titre principal -->
        <div class="text-center mb-16">
            <h2 class="font-bebas text-6xl md:text-7xl text-noir mb-6 tracking-wide">
                COMMENT ÇA MARCHE ?
            </h2>
            <p class="font-anek text-xl text-noir/70 max-w-3xl mx-auto">
                Découvrez le processus démocratique et transparent de notre élection annuelle du combattant MMA de l'année
            </p>        </div>       
        <!-- Étapes par ligne de 2 -->
        <div class="max-w-6xl mx-auto">
            <!-- Ligne 1 - Étapes 1 et 2 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Étape 1 -->
                <div class="step-card opacity-0 translate-y-8 transition-all duration-1000" id="step-1">
                    <div class="bg-white p-6 rounded-xl shadow-lg h-full">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-rouge rounded-full flex items-center justify-center mr-4">
                                <span class="font-bebas text-xl text-white">01</span>
                            </div>
                            <h3 class="font-bebas text-2xl text-rouge tracking-wide">
                                INSCRIPTION
                            </h3>
                        </div>
                        <div class="pl-16">
                            <p class="font-anek text-noir/80 text-base leading-relaxed mb-3">
                                <strong class="text-rouge">Public :</strong> Créez votre compte unique (une inscription par machine)
                            </p>
                            <p class="font-anek text-noir/80 text-base leading-relaxed">
                                <strong class="text-bleu">Professionnels :</strong> Code unique par email
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Étape 2 -->
                <div class="step-card opacity-0 translate-y-8 transition-all duration-1000" id="step-2">
                    <div class="bg-white p-6 rounded-xl shadow-lg h-full">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-bleu rounded-full flex items-center justify-center mr-4">
                                <span class="font-bebas text-xl text-white">02</span>
                            </div>
                            <h3 class="font-bebas text-2xl text-bleu tracking-wide">
                                DÉCOUVERTE
                            </h3>
                        </div>
                        <div class="pl-16">
                            <p class="font-anek text-noir/80 text-base leading-relaxed mb-3">
                                Explorez les profils des combattants avec photos, palmarès et highlights vidéo
                            </p>
                            <div class="flex items-center text-dore font-anek font-medium text-sm">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"/>
                                </svg>
                                Performances 2025 uniquement
                            </div>
                        </div>
                    </div>
                </div>
            </div>            <!-- Ligne 2 - Étapes 3 et 4 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Étape 3 -->
                <div class="step-card opacity-0 translate-y-8 transition-all duration-1000" id="step-3">
                    <div class="bg-white p-6 rounded-xl shadow-lg h-full">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-dore rounded-full flex items-center justify-center mr-4">
                                <span class="font-bebas text-xl text-noir">03</span>
                            </div>
                            <h3 class="font-bebas text-2xl text-dore tracking-wide">
                                VOTE SÉCURISÉ
                            </h3>
                        </div>
                        <div class="pl-16">
                            <p class="font-anek text-noir/80 text-base leading-relaxed mb-3">
                                Sélectionnez votre combattant favori. Vote unique et anonyme.
                            </p>
                            <div class="grid grid-cols-1 gap-2 text-xs font-anek font-medium">
                                <div class="text-rouge text-center p-2 bg-rouge/10 rounded">Public: 20%</div>
                                <div class="text-bleu text-center p-2 bg-bleu/10 rounded">Journalistes: 40%</div>
                                <div class="text-dore text-center p-2 bg-dore/10 rounded">Coachs: 40%</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Étape 4 -->
                <div class="step-card opacity-0 translate-y-8 transition-all duration-1000" id="step-4">
                    <div class="bg-white p-6 rounded-xl shadow-lg h-full">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-noir rounded-full flex items-center justify-center mr-4">
                                <span class="font-bebas text-xl text-white">04</span>
                            </div>
                            <h3 class="font-bebas text-2xl text-noir tracking-wide">
                                RÉSULTATS
                            </h3>
                        </div>
                        <div class="pl-16">
                            <p class="font-anek text-noir/80 text-base leading-relaxed mb-3">
                                Découvrez le vainqueur grâce au calcul automatique pondéré
                            </p>
                            <div class="bg-noir p-3 rounded-lg">
                                <p class="font-anek text-white font-medium text-center text-sm">
                                    🏆 Combattant de l'année
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-16">
            <div class="bg-noir p-8 rounded-2xl shadow-lg">
                <h3 class="font-bebas text-4xl text-white mb-4 tracking-wide">
                    PRÊT À VOTER ?
                </h3>
                <p class="font-anek text-gris-clair/90 text-lg mb-6 max-w-2xl mx-auto">
                    Rejoignez des milliers de passionnés et participez à l'élection la plus attendue du MMA
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="../register.php" class="inline-flex items-center justify-center px-8 py-4 font-anek font-medium text-noir bg-dore hover:bg-dore/90 rounded-lg transition-all duration-200">
                        S'inscrire (Public)
                    </a>
                    <a href="../login.php" class="inline-flex items-center justify-center px-8 py-4 font-anek font-medium text-white border-2 border-white hover:bg-white hover:text-noir rounded-lg transition-all duration-200">
                        Se connecter
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Animation simple par ligne d'étapes -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const steps = document.querySelectorAll('.step-card');
    let currentStep = 0;
    
    function showNextSteps() {
        // Afficher 2 étapes à la fois (ligne par ligne)
        for (let i = 0; i < 2 && currentStep < steps.length; i++) {
            steps[currentStep].classList.remove('opacity-0', 'translate-y-8');
            steps[currentStep].classList.add('opacity-100', 'translate-y-0');
            currentStep++;
        }
        
        // Afficher la prochaine ligne après 2 secondes
        if (currentStep < steps.length) {
            setTimeout(showNextSteps, 2000);
        }
    }
    
    // Démarrer l'animation après un court délai
    setTimeout(showNextSteps, 500);
});
</script>

<?php include '../../includes/footer.php'; ?>