<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';
?>

<main class="flex-1 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-4xl font-bold text-noir mb-6">A propos</h1>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">Le Projet</h2>
            <p class="text-gray-700 mb-4">
                Cette application de vote en ligne a ete developpee dans le cadre du projet SAE3 
                de BUT Informatique a l'IUT de Saint-Die-des-Vosges.
            </p>
            <p class="text-gray-700 mb-4">
                L'objectif est de permettre l'election annuelle du combattant de MMA ayant realise 
                la performance la plus remarquable de l'annee, en combinant l'avis du public et 
                l'expertise des professionnels.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">L'Equipe</h2>
            <p class="text-gray-700 mb-3">
                <strong>Etudiants developpeurs :</strong>
            </p>
            <ul class="list-disc list-inside text-gray-700 mb-4 ml-4">
                <li>Lucas Charpentier</li>
                <li>Ahmed Errebache</li>
            </ul>
            <p class="text-gray-700">
                <strong>Formation :</strong> BUT Informatique<br>
                <strong>Etablissement :</strong> IUT de Saint-Die-des-Vosges - Universite de Lorraine<br>
                <strong>Annee universitaire :</strong> 2025-2026
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">Le Systeme de Vote</h2>
            <p class="text-gray-700 mb-4">
                Le vote repose sur une ponderation equilibree entre trois colleges electoraux :
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="border border-gray-200 rounded p-4 text-center">
                    <h3 class="font-semibold text-bleu mb-2">Journalistes</h3>
                    <p class="text-3xl font-bold text-noir">40%</p>
                </div>
                <div class="border border-gray-200 rounded p-4 text-center">
                    <h3 class="font-semibold text-bleu mb-2">Coachs</h3>
                    <p class="text-3xl font-bold text-noir">40%</p>
                </div>
                <div class="border border-gray-200 rounded p-4 text-center">
                    <h3 class="font-semibold text-bleu mb-2">Public</h3>
                    <p class="text-3xl font-bold text-noir">20%</p>
                </div>
            </div>
            <p class="text-gray-700">
                Cette repartition garantit un equilibre entre l'expertise professionnelle 
                et l'avis des fans de MMA.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">Contexte du Projet</h2>
            <p class="text-gray-700 mb-4">
                L'election du combattant de MMA de l'annee constitue un moment important pour 
                la reconnaissance sportive. Elle ne vise pas a designer "le plus fort", mais 
                le combattant qui a le plus marque l'annee par ses performances.
            </p>
            <p class="text-gray-700 mb-4">
                L'evaluation ne se limite pas au rang de l'adversaire battu : la maniere compte 
                tout autant. Ce systeme valorise ainsi a la fois l'exploit sportif et la dimension 
                spectaculaire qui caracterisent les arts martiaux mixtes modernes.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold text-bleu mb-4">Contact</h2>
            <p class="text-gray-700 mb-2">
                Pour toute question ou remarque concernant cette application :
            </p>
            <p class="text-gray-700">
                <strong>Email :</strong> contact@mma-election.fr<br>
                <strong>Institution :</strong> IUT de Saint-Die-des-Vosges
            </p>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
