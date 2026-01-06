<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';
?>

<main class="flex-1 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-4xl font-bold text-noir mb-6">Licence et Propriete Intellectuelle</h1>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <p class="text-gray-700 mb-4">
                Cette page detaille les informations relatives a la propriete intellectuelle 
                et aux droits d'auteur de l'application de vote en ligne MMA.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">1. Titulaires des droits</h2>
            <p class="text-gray-700 mb-4">
                Le code source, l'architecture logicielle et l'interface graphique de cette 
                application constituent des oeuvres de l'esprit protegees par le droit d'auteur 
                conformement au Code de la propriete intellectuelle (CPI).
            </p>
            <p class="text-gray-700 mb-3">
                <strong>Auteurs et titulaires des droits :</strong>
            </p>
            <ul class="list-disc list-inside text-gray-700 ml-4 space-y-2">
                <li>Lucas Charpentier</li>
                <li>Ahmed Errebache</li>
            </ul>
            <p class="text-gray-700 mt-4">
                Les etudiants developpeurs sont titulaires des droits patrimoniaux et moraux 
                sur leurs creations, sauf cession explicite.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">2. Droits d'auteur</h2>
            <p class="text-gray-700 mb-4">
                Copyright &copy; 2025-2026 Lucas Charpentier & Ahmed Errebache
            </p>
            <p class="text-gray-700 mb-4">
                Tous droits reserves. L'utilisation, la reproduction, la modification ou la 
                distribution de tout ou partie de cette application sans autorisation prealable 
                ecrite des auteurs est strictement interdite.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">3. Elements proteges</h2>
            <p class="text-gray-700 mb-3">
                Les elements suivants sont proteges par le droit d'auteur :
            </p>
            <ul class="list-disc list-inside text-gray-700 ml-4 space-y-2">
                <li>Le code source de l'application (PHP, JavaScript, HTML, CSS)</li>
                <li>L'architecture logicielle et la structure de la base de donnees</li>
                <li>L'interface graphique et le design visuel</li>
                <li>Les algorithmes et la logique metier</li>
                <li>La documentation technique et utilisateur</li>
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">4. Cadre du projet</h2>
            <p class="text-gray-700 mb-4">
                Cette application a ete developpee dans le cadre du projet SAE3 (Situation 
                d'Apprentissage et d'Evaluation) du BUT Informatique a l'IUT de Saint-Die-des-Vosges, 
                Universite de Lorraine.
            </p>
            <p class="text-gray-700 mb-4">
                <strong>Annee universitaire :</strong> 2025-2026<br>
                <strong>Formation :</strong> BUT Informatique<br>
                <strong>Etablissement :</strong> IUT de Saint-Die-des-Vosges
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">5. Technologies utilisees</h2>
            <p class="text-gray-700 mb-3">
                Cette application utilise les technologies suivantes :
            </p>
            <ul class="list-disc list-inside text-gray-700 ml-4 space-y-2">
                <li>PHP 8.0+ pour le back-end</li>
                <li>MySQL/MariaDB pour la base de donnees</li>
                <li>HTML5, CSS3 et JavaScript pour le front-end</li>
                <li>Tailwind CSS pour le design</li>
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">6. Bibliotheques tierces</h2>
            <p class="text-gray-700 mb-4">
                Cette application peut utiliser des bibliotheques et frameworks open source. 
                Chaque bibliotheque tierce reste soumise a sa propre licence d'utilisation.
            </p>
            <p class="text-gray-700">
                Les credits et licences des bibliotheques utilisees sont disponibles dans 
                la documentation technique du projet.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">7. Utilisation autorisee</h2>
            <p class="text-gray-700 mb-3">
                L'utilisation de cette application est strictement limitee aux cas suivants :
            </p>
            <ul class="list-disc list-inside text-gray-700 ml-4 space-y-2">
                <li>Evaluation academique dans le cadre du projet SAE3</li>
                <li>Demonstration du systeme de vote pour le projet MMA</li>
                <li>Consultation a des fins educatives</li>
            </ul>
            <p class="text-gray-700 mt-4">
                Toute autre utilisation, notamment commerciale, necessite une autorisation 
                prealable ecrite des auteurs.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">8. Interdictions</h2>
            <p class="text-gray-700 mb-3">
                Sans autorisation explicite des auteurs, il est strictement interdit de :
            </p>
            <ul class="list-disc list-inside text-gray-700 ml-4 space-y-2">
                <li>Copier, reproduire ou distribuer le code source</li>
                <li>Modifier, adapter ou creer des oeuvres derivees</li>
                <li>Utiliser l'application a des fins commerciales</li>
                <li>Retirer ou modifier les mentions de droits d'auteur</li>
                <li>Reverse engineering du code ou de l'architecture</li>
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">9. Mentions legales</h2>
            <p class="text-gray-700 mb-4">
                <strong>Editeur :</strong> Lucas Charpentier & Ahmed Errebache<br>
                <strong>Hebergeur :</strong> IUT de Saint-Die-des-Vosges<br>
                <strong>Contact :</strong> contact@mma-election.fr
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">10. Contact</h2>
            <p class="text-gray-700">
                Pour toute question concernant la licence, les droits d'auteur ou l'utilisation 
                de cette application, veuillez nous contacter a l'adresse suivante : 
                <strong>contact@mma-election.fr</strong>
            </p>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
