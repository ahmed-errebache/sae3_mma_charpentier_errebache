<?php
session_start();
require_once '../includes/config.php';

$pageTitle = "Politique des cookies";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - MMA Fighter Election</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<?php require_once '../includes/header.php'; ?>

<div class="container mx-auto px-4 py-12 max-w-4xl">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Politique des cookies</h1>
        
        <div class="space-y-6 text-gray-700">
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">Qu'est-ce qu'un cookie ?</h2>
                <p>
                    Un cookie est un petit fichier texte stocké sur votre appareil lors de votre visite sur notre site. 
                    Les cookies nous permettent d'améliorer votre expérience de navigation et de garantir le bon fonctionnement de notre application.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">Types de cookies utilisés</h2>
                
                <div class="space-y-4">
                    <div class="border-l-4 border-bleu pl-4">
                        <h3 class="font-semibold text-gray-900">Cookies essentiels</h3>
                        <p class="mt-2">
                            Ces cookies sont indispensables au fonctionnement de l'application. Ils permettent :
                        </p>
                        <ul class="list-disc list-inside mt-2 ml-4">
                            <li>La gestion de votre session et de votre authentification</li>
                            <li>La sécurisation de votre navigation</li>
                            <li>Le maintien de votre état de connexion</li>
                        </ul>
                        <p class="mt-2 text-sm text-gray-600">
                            <strong>Durée de conservation :</strong> Jusqu'à la fin de votre session
                        </p>
                    </div>

                    <div class="border-l-4 border-gray-400 pl-4">
                        <h3 class="font-semibold text-gray-900">Cookies de consentement</h3>
                        <p class="mt-2">
                            Ce cookie enregistre votre choix concernant l'utilisation des cookies sur notre site.
                        </p>
                        <p class="mt-2 text-sm text-gray-600">
                            <strong>Durée de conservation :</strong> 12 mois
                        </p>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">Gestion de vos préférences</h2>
                <p>
                    Lors de votre première visite, un bandeau vous permet de choisir d'accepter ou de refuser les cookies non essentiels. 
                    Vous pouvez à tout moment modifier vos préférences en supprimant les cookies de votre navigateur.
                </p>
                <div class="mt-4 p-4 bg-gray-100 rounded">
                    <p class="font-semibold mb-2">Pour gérer les cookies dans votre navigateur :</p>
                    <ul class="list-disc list-inside space-y-1 ml-4">
                        <li><strong>Chrome :</strong> Paramètres > Confidentialité et sécurité > Cookies</li>
                        <li><strong>Firefox :</strong> Paramètres > Vie privée et sécurité > Cookies</li>
                        <li><strong>Safari :</strong> Préférences > Confidentialité</li>
                        <li><strong>Edge :</strong> Paramètres > Cookies et autorisations de site</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">Cookies et vote en ligne</h2>
                <p>
                    Dans le cadre du système de vote, certaines données techniques sont nécessaires pour :
                </p>
                <ul class="list-disc list-inside mt-2 ml-4">
                    <li>Garantir qu'un utilisateur ne vote qu'une seule fois</li>
                    <li>Assurer la sécurité du scrutin</li>
                    <li>Prévenir les tentatives de fraude</li>
                </ul>
                <p class="mt-2 text-sm italic">
                    Ces traitements sont conformes au RGPD et respectent le secret du vote. 
                    Aucune corrélation n'est établie entre votre identité et votre choix de vote.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">Vos droits</h2>
                <p>
                    Conformément au RGPD, vous disposez d'un droit d'accès, de rectification et de suppression de vos données personnelles. 
                    Pour exercer ces droits, contactez-nous à : 
                    <a href="mailto:contact@mmafighterelection.fr" class="text-bleu hover:underline">contact@mmafighterelection.fr</a>
                </p>
            </section>

            <section class="bg-blue-50 p-4 rounded">
                <p class="text-sm">
                    <strong>Dernière mise à jour :</strong> Janvier 2026
                </p>
            </section>
        </div>

        <div class="mt-8 pt-6 border-t">
            <a href="../index.php" class="text-bleu hover:underline">Retour à l'accueil</a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>
