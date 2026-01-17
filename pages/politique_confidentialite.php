<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/config.php';
require_once '../includes/header.php';
?>

<main class="flex-1 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-4xl font-bold text-noir mb-6">Politique de Confidentialite</h1>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <p class="text-sm text-gray-600 mb-4">Derniere mise a jour : 06 janvier 2026</p>
            <p class="text-gray-700 mb-4">
                Cette politique de confidentialite explique comment nous collectons, utilisons et 
                protegens vos donnees personnelles dans le cadre de l'utilisation de cette application 
                de vote en ligne.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">1. Responsable du traitement</h2>
            <p class="text-gray-700">
                Le responsable du traitement des donnees personnelles est l'equipe de developpement 
                composee de Lucas Charpentier et Ahmed Errebache, dans le cadre du projet SAE3 
                de l'IUT de Saint-Die-des-Vosges.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">2. Donnees collectees</h2>
            <p class="text-gray-700 mb-3">
                Nous collectons uniquement les donnees strictement necessaires au deroulement du vote :
            </p>
            <ul class="list-disc list-inside text-gray-700 ml-4 space-y-2">
                <li><strong>Pour les electeurs du public :</strong> nom, prenom, email, adresse IP</li>
                <li><strong>Pour les professionnels :</strong> nom, prenom, email, code d'authentification</li>
                <li><strong>Donnees de vote :</strong> candidat choisi, date et heure du vote</li>
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">3. Finalite du traitement</h2>
            <p class="text-gray-700 mb-3">
                Les donnees collectees sont utilisees exclusivement pour :
            </p>
            <ul class="list-disc list-inside text-gray-700 ml-4 space-y-2">
                <li>Permettre l'authentification des electeurs</li>
                <li>Garantir l'unicite du vote (une personne = un vote)</li>
                <li>Calculer les resultats selon la ponderation definie</li>
                <li>Etablir des statistiques globales anonymes</li>
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">4. Base juridique (RGPD)</h2>
            <p class="text-gray-700 mb-4">
                Le traitement de vos donnees repose sur votre consentement, recueilli lors de 
                l'inscription ou de la connexion. Conformement au Reglement General sur la 
                Protection des Donnees (RGPD - reglement UE 2016/679), vous disposez de droits 
                sur vos donnees personnelles.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">5. Secret du vote</h2>
            <p class="text-gray-700 mb-4">
                Le vote est totalement anonyme. Une fois valide, il est impossible d'associer 
                un vote a une personne specifique. Les donnees d'identification et les votes 
                sont stockes de maniere separee et non reversible.
            </p>
            <p class="text-gray-700">
                Cette separation technique garantit structurellement le secret du vote, 
                independamment de la confiance accordee aux administrateurs.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">6. Duree de conservation</h2>
            <p class="text-gray-700 mb-3">
                Les donnees personnelles sont conservees selon les durees suivantes :
            </p>
            <ul class="list-disc list-inside text-gray-700 ml-4 space-y-2">
                <li>Pendant la periode de vote : conservation complete des donnees</li>
                <li>Apres la cloture du scrutin : suppression des donnees personnelles identifiantes</li>
                <li>Donnees statistiques anonymes : conservation indefinie</li>
                <li>Adresses IP : suppression dans les 30 jours suivant la fin du scrutin</li>
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">7. Securite des donnees</h2>
            <p class="text-gray-700 mb-3">
                Nous mettons en oeuvre des mesures de securite appropriees :
            </p>
            <ul class="list-disc list-inside text-gray-700 ml-4 space-y-2">
                <li>Stockage securise sur serveur conforme aux normes europeennes</li>
                <li>Connexions chiffrees (HTTPS)</li>
                <li>Acces restreint aux donnees</li>
                <li>Sauvegardes regulieres</li>
                <li>Separation technique des donnees d'identification et des votes</li>
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">8. Vos droits</h2>
            <p class="text-gray-700 mb-3">
                Conformement au RGPD, vous disposez des droits suivants :
            </p>
            <ul class="list-disc list-inside text-gray-700 ml-4 space-y-2">
                <li><strong>Droit d'acces :</strong> consulter les donnees que nous detenons sur vous</li>
                <li><strong>Droit de rectification :</strong> corriger vos donnees personnelles</li>
                <li><strong>Droit de suppression :</strong> supprimer vos donnees (sauf adresse IP necessaire au vote)</li>
                <li><strong>Droit d'opposition :</strong> vous opposer au traitement de vos donnees</li>
                <li><strong>Droit a la portabilite :</strong> recuperer vos donnees dans un format lisible</li>
            </ul>
            <p class="text-gray-700 mt-4">
                Pour exercer ces droits, contactez-nous a : <strong>contact@mma-election.fr</strong>
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">9. Partage des donnees</h2>
            <p class="text-gray-700">
                Vos donnees personnelles ne sont jamais transmises a des tiers. Elles sont utilisees 
                uniquement dans le cadre du processus de vote et ne font l'objet d'aucune 
                commercialisation.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">10. Cookies</h2>
            <p class="text-gray-700">
                Cette application utilise uniquement des cookies de session strictement necessaires 
                au fonctionnement du systeme d'authentification. Aucun cookie de suivi ou publicitaire 
                n'est utilise.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">11. Contact</h2>
            <p class="text-gray-700">
                Pour toute question concernant cette politique de confidentialite ou vos donnees 
                personnelles, vous pouvez nous contacter a l'adresse suivante : 
                <strong>contact@mma-election.fr</strong>
            </p>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
