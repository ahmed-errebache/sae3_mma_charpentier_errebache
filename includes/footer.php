   <!-- JavaScript -->
    <script src="assets/js/countdown.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/cookies.js"></script>
<!-- fin des "div" placés dans le header -->
</div>
</div>

<!-- Bandeau de consentement des cookies -->
<div id="cookie-banner" class="hidden fixed bottom-0 left-0 right-0 bg-noir/95 text-white p-6 shadow-2xl z-50 border-t-4 border-rouge">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex-1">
            <h3 class="text-lg font-bebas text-dore mb-2">Ce site utilise des cookies</h3>
            <p class="text-sm text-gray-300">
                Nous utilisons des cookies pour améliorer votre expérience sur notre site. 
                Vous pouvez accepter tous les cookies ou uniquement les cookies essentiels.
                <a href="pages/politique_cookies.php" class="text-rouge hover:text-rouge/80 underline ml-1">En savoir plus</a>
            </p>
        </div>
        <div class="flex gap-3 flex-shrink-0">
            <button id="accept-essential-cookies" class="px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white rounded-md transition-colors duration-200 text-sm font-medium">
                Essentiels uniquement
            </button>
            <button id="accept-all-cookies" class="px-6 py-2 bg-rouge hover:bg-rouge/80 text-white rounded-md transition-colors duration-200 text-sm font-medium">
                Accepter tous
            </button>
        </div>
    </div>
</div>

<?php
$base_path = (basename(dirname($_SERVER['PHP_SELF'])) === 'pages') ? '' : 'pages/';
?>

<footer class="bg-neutral-primary-soft rounded-base shadow-xs border border-default m-4">
    <div class="w-full mx-auto max-w-screen-xl p-4 flex flex-col items-center text-center md:flex-row md:items-center md:justify-between md:text-left">
      <span class="text-sm text-body">Lucas Charpentier, Ahmed Errebache.<br class="md:hidden"> <a href="#" class="hover:underline">MMA Fighter Election</a>
    </span>
    <ul class="flex flex-col md:flex-row md:flex-wrap items-center mt-3 text-sm font-medium text-body md:mt-0">
        <li class="mb-2 md:mb-0">
            <a href="<?php echo $base_path; ?>a_propos.php" class="hover:underline md:me-4 md:md:me-6">A propos</a>
        </li>
        <li class="mb-2 md:mb-0">
            <a href="<?php echo $base_path; ?>politique_confidentialite.php" class="hover:underline md:me-4 md:md:me-6">Politique de confidentialite</a>
        </li>
        <li>
            <a href="<?php echo $base_path; ?>licence.php" class="hover:underline">Licence</a>
        </li>
    </ul>
    </div>
</footer>
</body>
</html>
