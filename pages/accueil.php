<?php

session_start();

include '../includes/config.php'; 

$connexion = dbconnect();

?>


<?php include '../includes/header.php'; ?>


<!-- Exemple avec Tailwind CSS -->
<div class="container mx-auto p-8">
    <h1 class="text-4xl font-bold text-mma-red mb-6">Tailwind CSS</h1>
    
    <!-- Boutons de test -->
        <button class="bg-mma-red hover:bg-red-700 text-white px-6 py-3 rounded-lg transition-colors">
            Bouton Rouge
        </button>
    
    <!-- Cards de test -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-bold mb-3">Card 1</h3>
            <p class="text-gray-600">Exemple de card avec Tailwind CSS</p>
        </div>
    </div>
</div>


<?php include '../includes/footer.php'; ?>