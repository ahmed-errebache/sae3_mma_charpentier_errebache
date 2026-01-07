   <!-- JavaScript -->
    <script src="assets/js/countdown.js"></script>
    <script src="assets/js/main.js"></script>
<!-- fin des "div" placés dans le header -->
</div>
</div>

<?php
$base_path = (basename(dirname($_SERVER['PHP_SELF'])) === 'pages') ? '' : 'pages/';
?>

<footer class="bg-neutral-primary-soft rounded-base shadow-xs border border-default m-4">
    <div class="w-full mx-auto max-w-screen-xl p-4 md:flex md:items-center md:justify-between">
      <span class="text-sm text-body sm:text-center">Lucas Charpentier, Ahmed Errebache. <a href="#" class="hover:underline">MMA Fighter Election</a>
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
