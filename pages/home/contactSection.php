<?php
/**
 * Section Contact - Redirection vers la page d'aide
 * Permet aux utilisateurs de poser leurs questions facilement
 */
?>

<section class="py-16 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center">
            
            <!-- Titre de la section -->
            <h2 class="font-bebas text-6xl md:text-7xl text-gray-800 mb-6 tracking-wide">
                UNE QUESTION ?
            </h2>
            <p class="font-anek text-xl text-gray-600 max-w-3xl mx-auto mb-12">
                Notre équipe est là pour vous accompagner dans votre expérience de vote
            </p>

            <!-- Formulaire de contact simplifié -->
            <div class="max-w-2xl mx-auto">
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <div class="text-center">
                        
                        <!-- Icône principale -->
                        <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>

                        <!-- Contenu informatif -->
                        <h3 class="font-bebas text-3xl text-gray-800 tracking-wide mb-4">
                            CONTACTEZ-NOUS
                        </h3>
                        <p class="font-anek text-gray-600 text-lg mb-8 leading-relaxed">
                            Besoin d'aide pour voter ? Un problème technique ? 
                            <br class="hidden md:block">
                            Notre équipe vous répond rapidement.
                        </p>

                        <!-- Bouton principal -->
                        <a href="../contact.php" class="inline-block bg-blue-500 text-white font-bebas text-xl px-8 py-4 rounded-lg tracking-wide hover:bg-blue-600 transition-colors duration-300">
                            NOUS CONTACTER
                        </a>
                    </div>
                </div>
            </div>

            <!-- Message de motivation -->
            <div class="mt-12">
                <p class="font-anek text-gray-500 text-sm max-w-lg mx-auto">
                    Votre voix compte dans l'élection du combattant MMA de l'année 2025
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Script simple pour l'animation de la section -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Observer pour détecter quand la section devient visible
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
            }
        });
    }, { threshold: 0.3 });

    // On observe la section contact
    const contactSection = document.querySelector('section:has(.bg-blue-500)');
    if (contactSection) {
        observer.observe(contactSection);
    }
});
</script>

<!-- Styles pour l'animation -->
<style>
/* Animation d'apparition depuis le bas */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out;
}
</style>