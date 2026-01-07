<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

$action = $_GET['action'] ?? 'liste';
$baseUrl = '/sae3_mma_charpentier_errebache';

$isAdmin = isset($_SESSION['isConnected']) && $_SESSION['isConnected'] && $_SESSION['user_type'] === 'administrateur';

// Si action finaliser, rediriger vers candidat.php
if ($action === 'finaliser') {
    header('Location: candidat.php' . (isset($_GET['etape']) ? '?etape=' . $_GET['etape'] : ''));
    exit();
}

// Si admin, rediriger vers admin/index.php
if ($isAdmin) {
    header('Location: ../admin/index.php' . (isset($_GET['edit']) ? '?edit=' . $_GET['edit'] : ''));
    exit();
}

$conn = dbconnect();
$sql = "SELECT * FROM candidat WHERE compte_verifie = 1 AND compte_actif = 1 ORDER BY nom, prenom";
$stmt = $conn->query($sql);
$candidats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidats - MMA Fighter Election</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        rouge: '#DE1315',
                        bleu: '#1847C7',
                        dore: '#D5A845',
                        'gris-clair': '#F3F3F3',
                        noir: '#19191E'
                    },
                    fontFamily: {
                        'bebas': ['Bebas Neue', 'sans-serif'],
                        'anek': ['Anek Bangla', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Anek+Bangla:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gris-clair font-anek">
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main class="container mx-auto px-4 py-8 mt-20 2xl:mt-8">
        <!-- Titre -->
        <div class="text-center mb-12 2xl:mb-6">
            <h1 class="text-5xl md:text-6xl font-bebas text-noir mb-4">NOS COMBATTANTS</h1>
            <p class="text-xl text-gray-700">Découvrez les candidats de l'année</p>
        </div>

        <!-- Carousel Container -->
        <div class="relative max-w-7xl mx-auto mb-16">
            <div id="carousel" class="flex items-center justify-center gap-4 px-20">
                <!-- Cards seront insérées dynamiquement -->
            </div>
            
            <!-- Boutons de navigation -->
            <button onclick="prevSlide()" class="absolute left-0 top-1/2 -translate-y-1/2 bg-rouge text-white p-4 rounded-full shadow-lg hover:bg-rouge/90 transition z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button onclick="nextSlide()" class="absolute right-0 top-1/2 -translate-y-1/2 bg-rouge text-white p-4 rounded-full shadow-lg hover:bg-rouge/90 transition z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            
            <!-- Indicateurs -->
            <div id="indicators" class="flex justify-center gap-2 mt-8">
                <!-- Indicateurs seront insérés dynamiquement -->
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script>
        const candidats = <?php echo json_encode($candidats); ?>;
        const baseUrl = '<?php echo $baseUrl; ?>';
        let currentIndex = 0;
        let autoPlayInterval;

        function createCard(candidat, position) {
            const photoUrl = candidat.photo_profil 
                ? `${baseUrl}/${candidat.photo_profil}` 
                : `${baseUrl}/assets/img/default-avatar.png`;
            
            let sizeClasses = '';
            let textSize = '';
            
            if (position === 'center') {
                sizeClasses = 'w-80 h-[28rem] scale-110 z-20 shadow-2xl';
                textSize = 'text-3xl';
            } else if (position === 'near') {
                sizeClasses = 'w-64 h-80 opacity-80 z-10 scale-95 shadow-lg';
                textSize = 'text-2xl';
            } else {
                sizeClasses = 'w-48 h-64 opacity-50 z-0 scale-90 shadow-md';
                textSize = 'text-xl';
            }
            
            return `
                <div class="flex-shrink-0 bg-white rounded-xl overflow-hidden transition-all duration-500 ${sizeClasses}">
                    <div class="relative h-3/5 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-4">
                        <img src="${photoUrl}" 
                             alt="${candidat.nom} ${candidat.prenom}"
                             class="max-w-full max-h-full object-contain">
                    </div>
                    <div class="p-4 h-2/5 flex flex-col justify-center">
                        <h3 class="${textSize} font-bebas text-noir text-center mb-1">
                            ${candidat.nom} ${candidat.prenom}
                        </h3>
                        ${candidat.surnom ? `
                            <p class="text-center text-gray-600 italic mb-2 text-sm">
                                "${candidat.surnom}"
                            </p>
                        ` : ''}
                        <p class="text-center text-sm text-gray-600">
                             ${candidat.nationalite}
                        </p>
                    </div>
                </div>
            `;
        }

        function updateCarousel() {
            const carousel = document.getElementById('carousel');
            const farLeftIndex = (currentIndex - 2 + candidats.length) % candidats.length;
            const nearLeftIndex = (currentIndex - 1 + candidats.length) % candidats.length;
            const nearRightIndex = (currentIndex + 1) % candidats.length;
            const farRightIndex = (currentIndex + 2) % candidats.length;
            
            carousel.innerHTML = 
                createCard(candidats[farLeftIndex], 'far') +
                createCard(candidats[nearLeftIndex], 'near') +
                createCard(candidats[currentIndex], 'center') +
                createCard(candidats[nearRightIndex], 'near') +
                createCard(candidats[farRightIndex], 'far');
            
            updateIndicators();
        }

        function updateIndicators() {
            const indicators = document.getElementById('indicators');
            indicators.innerHTML = candidats.map((_, index) => `
                <button onclick="goToSlide(${index})" 
                        class="w-3 h-3 rounded-full transition-all ${index === currentIndex ? 'bg-rouge w-8' : 'bg-gray-300'}">
                </button>
            `).join('');
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % candidats.length;
            updateCarousel();
            resetAutoPlay();
        }

        function prevSlide() {
            currentIndex = (currentIndex - 1 + candidats.length) % candidats.length;
            updateCarousel();
            resetAutoPlay();
        }

        function goToSlide(index) {
            currentIndex = index;
            updateCarousel();
            resetAutoPlay();
        }

        function startAutoPlay() {
            autoPlayInterval = setInterval(nextSlide, 5000);
        }

        function resetAutoPlay() {
            clearInterval(autoPlayInterval);
            startAutoPlay();
        }

        // Initialiser le carousel
        updateCarousel();
        startAutoPlay();
    </script>
</body>
</html>
