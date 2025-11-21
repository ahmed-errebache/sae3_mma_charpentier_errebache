<?php
/**
 * PAGE CONTACT - ÉLECTION MMA 2025
 * ===================================
 */

// Démarrage de la session pour gérer l'utilisateur connecté
session_start();

// Connexion à la base de données
include '../includes/config.php'; 
$connexion = dbconnect();

// Chargement de l'en-tête avec navigation
include '../includes/header.php'; 
?>

<!-- FORMULAIRE DE CONTACT -->
<div class="min-h-screen bg-gris-clair py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!--  TITRE DE LA PAGE -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bebas text-noir mb-4 tracking-wide">
                CONTACT
            </h1>
            <p class="text-lg font-anek text-gray-600">
                Une question ? Contactez-nous
            </p>
        </div>

        <!-- Formulaire et informations côte à côte -->
        <div class="bg-white rounded-xl shadow-lg p-8 lg:p-12">
            <div class="grid lg:grid-cols-2 gap-12">
                
                <!-- Formulaire de contact -->
                <div>
                    <h2 class="text-2xl font-bebas text-noir mb-6 tracking-wide">
                        ENVOYEZ UN MESSAGE
                    </h2>
                    
                    <form action="#" method="POST" class="space-y-6">
                        
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom *
                            </label>
                            <input type="text" id="nom" name="nom" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rouge focus:border-rouge font-anek"
                                placeholder="Votre nom">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email *
                            </label>
                            <input type="email" id="email" name="email" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rouge focus:border-rouge font-anek"
                                placeholder="votre@email.com">
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Message *
                            </label>
                            <textarea id="message" name="message" rows="5" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rouge focus:border-rouge font-anek"
                                placeholder="Votre message..."></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-rouge text-white font-bebas text-lg py-3 px-6 rounded-lg tracking-wide hover:bg-rouge/90 transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-rouge/50 focus:ring-offset-2 shadow-lg">
                            ENVOYER
                        </button>
                    </form>
                </div>

                <div class="space-y-8">
                    <div>
                        <h3 class="text-xl font-bebas text-noir mb-4 tracking-wide">
                            INFORMATIONS
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-semibold text-gray-900 font-anek">Email</h4>
                                <p class="text-gray-600 font-anek">contact@mmafighterelection.fr</p>
                            </div>
                            
                            <div>
                                <h4 class="font-semibold text-gray-900 font-anek">Équipe</h4>
                                <p class="text-gray-600 font-anek">Lucas Charpentier & Ahmed Errebache</p>
                            </div>
                            
                            <div>
                                <h4 class="font-semibold text-gray-900 font-anek">Projet</h4>
                                <p class="text-gray-600 font-anek">SAÉ S3 - IUT de Saint Die</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-bebas text-noir mb-4 tracking-wide">
                            QUESTIONS FRÉQUENTES
                        </h3>
                        
                        <div class="space-y-3">
                            <div>
                                <h4 class="font-medium text-gray-900 font-anek text-sm">Comment voter ?</h4>
                                <p class="text-gray-600 font-anek text-sm">Inscrivez-vous ou connectez-vous pour participer au vote.</p>
                            </div>
                            
                            <div>
                                <h4 class="font-medium text-gray-900 font-anek text-sm">Code professionnel ?</h4>
                                <p class="text-gray-600 font-anek text-sm">Les codes sont envoyés aux journalistes et coachs référencés.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-12">
            <!-- Retour à l'accueil -->
            <a href="<?php echo $base_url; ?>/index.php" 
                class="inline-flex items-center px-6 py-3 bg-rouge text-white font-bebas text-lg rounded-lg tracking-wide hover:bg-rouge/90 transition-colors duration-200 mr-4">
                RETOUR ACCUEIL
            </a>
            <!-- Voir les candidats pour voter -->
            <a href="<?php echo $base_url; ?>/pages/candidats.php" 
                class="inline-flex items-center px-6 py-3 bg-transparent text-bleu border-2 border-bleu font-bebas text-lg rounded-lg tracking-wide hover:bg-bleu hover:text-white transition-colors duration-200">
                VOIR CANDIDATS
            </a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>