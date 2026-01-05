<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Vérifier que l'utilisateur est connecté et est un électeur
if (!isset($_SESSION['isConnected']) || $_SESSION['user_type'] !== 'electeur') {
    header('Location: /sae3_mma_charpentier_errebache/pages/login.php');
    exit;
}

$conn = dbconnect();
$baseUrl = '/sae3_mma_charpentier_errebache';

// Récupérer les informations de l'électeur
$sqlElecteur = "SELECT * FROM electeur WHERE email = :email";
$stmtElecteur = $conn->prepare($sqlElecteur);
$stmtElecteur->execute([':email' => $_SESSION['email']]);
$electeur = $stmtElecteur->fetch(PDO::FETCH_ASSOC);

if (!$electeur) {
    header('Location: /sae3_mma_charpentier_errebache/pages/login.php');
    exit;
}

$successMessage = '';
$errorMessage = '';
$voteExistant = null;
$peutVoter = false;
$messageVerification = '';
$scrutinActif = null;

// Traitement du formulaire de vote
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'voter' && isset($_POST['candidat_id']) && isset($electeur['ID_electeur'])) {
        $id_candidat = (int)$_POST['candidat_id'];
        $resultat = enregistrerVote($electeur['ID_electeur'], $id_candidat);
        
        if ($resultat['success']) {
            $successMessage = $resultat['message'];
            // Recharger les infos de l'électeur
            $stmtElecteur->execute([':email' => $_SESSION['email']]);
            $electeur = $stmtElecteur->fetch(PDO::FETCH_ASSOC);
        } else {
            $errorMessage = $resultat['message'];
        }
    }
}

// Vérifier si l'électeur peut voter (seulement si on a l'id)
if (isset($electeur['ID_electeur'])) {
    $verification = peutVoter($electeur['ID_electeur']);
    $peutVoter = $verification['peut_voter'];
    $messageVerification = $verification['message'];
    $scrutinActif = $verification['scrutin'];
    
    // Récupérer le vote existant si l'électeur a déjà voté
    if (!$peutVoter && $scrutinActif) {
        $voteExistant = getVoteElecteur($electeur['ID_electeur']);
    }
}

// Récupérer tous les candidats vérifiés du scrutin actif
$candidats = [];
if ($scrutinActif) {
    $sqlCandidats = "SELECT * FROM candidat 
                     WHERE compte_verifie = 1 
                     AND id_scrutin = :id_scrutin
                     ORDER BY nom, prenom";
    $stmtCandidats = $conn->prepare($sqlCandidats);
    $stmtCandidats->execute([':id_scrutin' => $scrutinActif['ID_scrutin']]);
    $candidats = $stmtCandidats->fetchAll(PDO::FETCH_ASSOC);
} else {
    $sqlCandidats = "SELECT * FROM candidat WHERE compte_verifie = 1 ORDER BY nom, prenom";
    $stmtCandidats = $conn->query($sqlCandidats);
    $candidats = $stmtCandidats->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter - MMA Election</title>
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

    <main class="container mx-auto px-4 py-8 mt-20">
        <!-- Titre -->
        <div class="text-center mb-12">
            <h1 class="text-5xl md:text-6xl font-bebas text-noir mb-4">
                VOTEZ POUR VOTRE COMBATTANT
            </h1>
            <?php if ($scrutinActif): ?>
                <p class="text-xl text-gray-700">
                    <?php echo htmlspecialchars($scrutinActif['nom_scrutin']); ?>
                </p>
                <p class="text-gray-600 mt-2">
                    Vote ouvert jusqu'au <?php echo date('d/m/Y à H:i', strtotime($scrutinActif['date_fermeture'])); ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Messages -->
        <?php if ($successMessage): ?>
            <div class="max-w-2xl mx-auto mb-8 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                <p class="font-medium"><?php echo htmlspecialchars($successMessage); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            <div class="max-w-2xl mx-auto mb-8 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <p class="font-medium"><?php echo htmlspecialchars($errorMessage); ?></p>
            </div>
        <?php endif; ?>

        <!-- Vote déjà effectué -->
        <?php if ($voteExistant): ?>
            <div class="max-w-3xl mx-auto mb-8 p-8 bg-white rounded-lg shadow-lg border-2 border-dore">
                <h2 class="text-3xl font-bebas text-noir mb-6 text-center">VOTRE VOTE</h2>
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="flex-shrink-0">
                        <?php 
                        $photoUrl = !empty($voteExistant['photo_profil']) 
                            ? $baseUrl . '/' . $voteExistant['photo_profil'] 
                            : $baseUrl . '/assets/img/default-avatar.png';
                        ?>
                        <img src="<?php echo htmlspecialchars($photoUrl); ?>" 
                             alt="<?php echo htmlspecialchars($voteExistant['nom'] . ' ' . $voteExistant['prenom']); ?>"
                             class="w-32 h-32 rounded-full object-cover border-4 border-dore">
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <p class="text-2xl font-bebas text-rouge">
                            <?php echo htmlspecialchars($voteExistant['nom'] . ' ' . $voteExistant['prenom']); ?>
                        </p>
                        <?php if (!empty($voteExistant['surnom'])): ?>
                            <p class="text-xl text-gray-700 italic">"<?php echo htmlspecialchars($voteExistant['surnom']); ?>"</p>
                        <?php endif; ?>
                        <p class="text-gray-600 mt-2">
                            <span class="inline-flex items-center gap-2">
                                🏁 <?php echo htmlspecialchars($voteExistant['nationalite']); ?>
                            </span>
                        </p>
                        <p class="text-sm text-gray-500 mt-4">
                            Vote enregistré le <?php echo date('d/m/Y à H:i', strtotime($voteExistant['date_vote'])); ?>
                        </p>
                    </div>
                </div>
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-bleu text-center">
                        ℹ️ Votre vote a été enregistré et ne peut plus être modifié.
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Message si impossible de voter -->
        <?php if (!$peutVoter && !$scrutinActif): ?>
            <div class="max-w-2xl mx-auto p-8 bg-white rounded-lg shadow-lg text-center">
                <div class="text-6xl mb-4">🗳️</div>
                <p class="text-xl text-gray-700"><?php echo htmlspecialchars($messageVerification); ?></p>
            </div>
        <?php endif; ?>

        <!-- Formulaire de vote -->
        <?php if ($peutVoter && !empty($candidats)): ?>
            <form id="voteForm" method="POST" class="max-w-7xl mx-auto">
                <input type="hidden" name="action" value="voter">
                
                <!-- Grille des candidats -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <?php foreach ($candidats as $candidat): ?>
                        <label class="cursor-pointer group">
                            <input type="radio" 
                                   name="candidat_id" 
                                   value="<?php echo htmlspecialchars($candidat['ID_candidat']); ?>"
                                   class="peer hidden"
                                   required>
                            
                            <div class="bg-white rounded-xl shadow-md overflow-hidden border-2 border-transparent peer-checked:border-rouge peer-checked:shadow-2xl transition-all duration-300 hover:shadow-xl hover:scale-105">
                                <!-- Photo -->
                                <div class="relative w-full h-40 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-3">
                                    <?php 
                                    $photoUrl = !empty($candidat['photo_profil']) 
                                        ? $baseUrl . '/' . $candidat['photo_profil'] 
                                        : $baseUrl . '/assets/img/default-avatar.png';
                                    ?>
                                    <img src="<?php echo htmlspecialchars($photoUrl); ?>" 
                                         alt="<?php echo htmlspecialchars($candidat['nom'] . ' ' . $candidat['prenom']); ?>"
                                         class="max-w-full max-h-full object-contain">
                                </div>
                                
                                <!-- Informations -->
                                <div class="p-4">
                                    <h3 class="text-xl font-bebas text-noir text-center mb-1">
                                        <?php echo htmlspecialchars($candidat['nom'] . ' ' . $candidat['prenom']); ?>
                                    </h3>
                                    
                                    <?php if (!empty($candidat['surnom'])): ?>
                                        <p class="text-center text-gray-600 italic mb-2">
                                            "<?php echo htmlspecialchars($candidat['surnom']); ?>"
                                        </p>
                                    <?php endif; ?>
                                    
                                    <p class="text-center text-sm text-gray-600">
                                        🏁 <?php echo htmlspecialchars($candidat['nationalite']); ?>
                                    </p>
                                    
                                    <!-- Badge sélectionné -->
                                    <div class="mt-3 text-center">
                                        <span class="hidden peer-checked:inline-flex items-center gap-1 px-4 py-2 bg-rouge text-white font-bebas text-sm rounded-full shadow-lg animate-pulse">
                                            ✓ SÉLECTIONNÉ
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>

                <!-- Bouton de confirmation -->
                <div class="text-center">
                    <button type="button" 
                            onclick="showConfirmModal()"
                            class="px-8 py-4 bg-rouge text-white font-bebas text-2xl rounded-lg hover:bg-rouge/90 transition-all duration-300 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                        CONFIRMER MON VOTE
                    </button>
                </div>
            </form>
        <?php elseif ($peutVoter && empty($candidats)): ?>
            <div class="max-w-2xl mx-auto p-8 bg-white rounded-lg shadow-lg text-center">
                <div class="text-6xl mb-4">👤</div>
                <h2 class="text-2xl font-bebas text-noir mb-4">AUCUN CANDIDAT DISPONIBLE</h2>
                <p class="text-gray-700">Aucun candidat n'a encore ete affecte au scrutin en cours.</p>
                <p class="text-gray-600 mt-2">L'administrateur doit affecter des candidats avant que le vote puisse commencer.</p>
            </div>
        <?php elseif (!$peutVoter && empty($candidats)): ?>
            <div class="max-w-2xl mx-auto p-8 bg-white rounded-lg shadow-lg text-center">
                <div class="text-6xl mb-4">👤</div>
                <p class="text-xl text-gray-700">Aucun candidat n'est actuellement disponible pour le vote.</p>
            </div>
        <?php elseif (!$peutVoter && !empty($candidats)): ?>
            <!-- Affichage des candidats en lecture seule -->
            <div class="max-w-7xl mx-auto">
                <div class="mb-8 p-4 bg-blue-50 rounded-lg text-center">
                    <p class="text-bleu font-medium">Liste des candidats (vote non disponible)</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php foreach ($candidats as $candidat): ?>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <!-- Photo -->
                            <div class="relative w-full h-40 bg-gray-100 flex items-center justify-center">
                                <?php 
                                $photoUrl = !empty($candidat['photo_profil']) 
                                    ? $baseUrl . '/' . $candidat['photo_profil'] 
                                    : $baseUrl . '/assets/img/default-avatar.png';
                                ?>
                                <img src="<?php echo htmlspecialchars($photoUrl); ?>" 
                                     alt="<?php echo htmlspecialchars($candidat['nom'] . ' ' . $candidat['prenom']); ?>"
                                     class="max-w-full max-h-full object-contain">
                            </div>
                            
                            <!-- Informations -->
                            <div class="p-4">
                                <h3 class="text-xl font-bebas text-noir text-center mb-1">
                                    <?php echo htmlspecialchars($candidat['nom'] . ' ' . $candidat['prenom']); ?>
                                </h3>
                                
                                <?php if (!empty($candidat['surnom'])): ?>
                                    <p class="text-center text-gray-600 italic mb-2">
                                        "<?php echo htmlspecialchars($candidat['surnom']); ?>"
                                    </p>
                                <?php endif; ?>
                                
                                <p class="text-center text-sm text-gray-600">
                                    🏁 <?php echo htmlspecialchars($candidat['nationalite']); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php elseif ($peutVoter && empty($candidats)): ?>
            <div class="max-w-2xl mx-auto p-8 bg-white rounded-lg shadow-lg text-center">
                <p class="text-xl text-gray-700">Aucun candidat n'est actuellement disponible pour le vote.</p>
            </div>
        <?php endif; ?>
    </main>

    <!-- Modal de confirmation -->
    <div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-8">
            <h3 class="text-3xl font-bebas text-noir mb-6 text-center">CONFIRMER VOTRE VOTE</h3>
            
            <div id="selectedCandidateInfo" class="mb-6 text-center">
                <!-- Rempli dynamiquement par JavaScript -->
            </div>
            
            <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4 mb-6">
                <p class="text-sm text-yellow-800 text-center">
                    ⚠️ <strong>Attention :</strong> Une fois confirmé, votre vote ne pourra plus être modifié.
                </p>
            </div>
            
            <div class="flex gap-4">
                <button type="button" 
                        onclick="hideConfirmModal()"
                        class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 font-bebas text-xl rounded-lg hover:bg-gray-50 transition">
                    ANNULER
                </button>
                <button type="button" 
                        onclick="confirmVote()"
                        class="flex-1 px-6 py-3 bg-rouge text-white font-bebas text-xl rounded-lg hover:bg-rouge/90 transition">
                    CONFIRMER
                </button>
            </div>
        </div>
    </div>

    <script>
        function showConfirmModal() {
            const selectedRadio = document.querySelector('input[name="candidat_id"]:checked');
            
            if (!selectedRadio) {
                alert('Veuillez sélectionner un candidat avant de confirmer votre vote.');
                return;
            }
            
            // Récupérer les infos du candidat sélectionné
            const candidateCard = selectedRadio.closest('label').querySelector('.p-4');
            const candidateName = candidateCard.querySelector('h3').textContent;
            const candidateSurname = candidateCard.querySelector('.italic')?.textContent || '';
            const candidateNationality = candidateCard.querySelector('.text-sm').textContent;
            const candidatePhoto = selectedRadio.closest('label').querySelector('img').src;
            
            // Remplir le modal
            document.getElementById('selectedCandidateInfo').innerHTML = `
                <img src="${candidatePhoto}" alt="${candidateName}" 
                     class="w-24 h-24 rounded-full object-cover mx-auto mb-4 border-4 border-dore">
                <p class="text-2xl font-bebas text-rouge mb-2">${candidateName}</p>
                ${candidateSurname ? `<p class="text-gray-600 italic mb-1">${candidateSurname}</p>` : ''}
                <p class="text-sm text-gray-600">${candidateNationality}</p>
            `;
            
            // Afficher le modal
            document.getElementById('confirmModal').classList.remove('hidden');
        }
        
        function hideConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }
        
        function confirmVote() {
            document.getElementById('voteForm').submit();
        }
        
        // Fermer le modal en cliquant à l'extérieur
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideConfirmModal();
            }
        });
    </script>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
