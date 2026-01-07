   <!-- JavaScript -->
    <script src="assets/js/countdown.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/cookies.js"></script>
<!-- fin des "div" placés dans le header -->
</div>
</div>

<!-- Banner de consentement des cookies -->
<div id="cookie-banner" class="hidden fixed bottom-0 left-0 right-0 bg-white shadow-lg border-t-2 border-bleu z-50">
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex-1 text-sm text-gray-700">
                <p class="font-semibold mb-2">Gestion des cookies</p>
                <p>
                    Nous utilisons des cookies essentiels pour assurer le bon fonctionnement de l'application de vote. 
                    En poursuivant votre navigation, vous acceptez leur utilisation. 
                    <a href="<?php echo isset($base_path) ? $base_path : 'pages/'; ?>politique_cookies.php" class="text-bleu hover:underline">En savoir plus</a>
                </p>
            </div>
            <div class="flex gap-3 flex-shrink-0">
                <button id="accept-essential-cookies" 
                        class="px-4 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-100 transition-colors">
                    Cookies essentiels uniquement
                </button>
                <button id="accept-all-cookies" 
                        class="px-4 py-2 text-sm bg-bleu text-white rounded-md hover:bg-bleu/90 transition-colors">
                    Tout accepter
                </button>
            </div>
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
        <li class="mb-2 md:mb-0">
            <a href="<?php echo $base_path; ?>politique_cookies.php" class="hover:underline md:me-4 md:md:me-6">Cookies</a>
        </li>
        <li>
            <a href="<?php echo $base_path; ?>licence.php" class="hover:underline">Licence</a>
        </li>
    </ul>
    </div>
</footer>
</body>
</html>
