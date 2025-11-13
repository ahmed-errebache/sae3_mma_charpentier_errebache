<?php
$page_title = "Test Tailwind - Plateforme MMA";
include '../../includes/header.php';
?>

<section class="bg-gris-clair dark:bg-noir">
    <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
        <div class="mr-auto place-self-center lg:col-span-7">
            <h1 class="max-w-2xl mb-4 font-bebas text-5xl md:text-6xl xl:text-7xl text-noir dark:text-white tracking-wide leading-tight">
                ELECTION DU COMBATTANT MMA DE L'ANNÉE
            </h1>
            <p class="max-w-2xl mb-6 font-anek font-normal text-noir/80 lg:mb-8 md:text-lg lg:text-xl dark:text-gris-clair/90">
                Une plateforme de vote transparente réunissant le public, les coachs et les journalistes
            </p>
            <a href="#" class="inline-flex items-center justify-center px-6 py-3 mr-4 font-anek font-medium text-center text-white rounded-lg bg-rouge hover:bg-rouge/90 focus:ring-4 focus:ring-rouge/30 transition-all duration-200">
                Se connecter
                <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </a>
            <a href="#" class="inline-flex items-center justify-center px-6 py-3 font-anek font-medium text-center text-noir border-2 border-bleu rounded-lg hover:bg-bleu hover:text-white focus:ring-4 focus:ring-bleu/30 dark:text-white dark:border-dore dark:hover:bg-dore dark:hover:text-noir transition-all duration-200">
                En savoir plus
            </a>
        </div>
        <div class="hidden lg:mt-0 lg:col-span-5 lg:flex">
            <img src="../../assets/img/hero-section.png" alt="hero section" class="w-full rounded-lg shadow-2xl" />
        </div>
    </div>
</section>
<?php include 'countdown.php'; ?>


<?php include '../../includes/footer.php'; ?>