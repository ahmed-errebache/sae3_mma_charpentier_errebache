<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/config.php';

$base_url = '/sae3_mma_charpentier_errebache';
$conn = dbconnect();

// Recuperer le dernier scrutin termine (date passee OU phase = resultat)
$query = "SELECT * FROM scrutin WHERE (date_fermeture < NOW() OR phase = 'resultat') ORDER BY annee DESC, ID_scrutin DESC LIMIT 1";
$stmt = $conn->query($query);
$scrutin_termine = $stmt->fetch();

// Recuperer le scrutin en cours (date dans la periode ET phase = vote)
$query_encours = "SELECT * FROM scrutin WHERE date_ouverture <= NOW() AND date_fermeture > NOW() AND phase = 'vote' ORDER BY annee DESC LIMIT 1";
$stmt_encours = $conn->query($query_encours);
$scrutin_encours = $stmt_encours->fetch();

$pageTitle = "Résultats";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
</head>
<body class="bg-gray-50">
    <?php include '../includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <?php if ($scrutin_encours): ?>
            <!-- Scrutin en cours : afficher uniquement le message et le bouton -->
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Scrutin en cours</h1>
                <p class="text-lg text-gray-700 mb-6">
                    Le vote est actuellement ouvert pour l'année <?php echo $scrutin_encours['annee']; ?>. Les résultats seront disponibles après le <?php echo date('d/m/Y', strtotime($scrutin_encours['date_fermeture'])); ?>.
                </p>
                <a href="<?php echo $base_url; ?>/pages/voter.php" class="inline-block bg-red-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-red-700 transition">
                    Voter maintenant
                </a>
            </div>
        <?php elseif (!$scrutin_termine): ?>
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <h1 class="text-2xl font-bold text-gray-800 mb-3">Aucun scrutin disponible</h1>
                <p class="text-gray-600">Les résultats seront affichés après le vote.</p>
            </div>
        <?php else:
            // Le scrutin est termine, on affiche les resultats
            $data_resultats = calculerResultatsScrutin($scrutin_termine['ID_scrutin']);
                
                if (empty($data_resultats) || empty($data_resultats['resultats'])): ?>
                    <div class="bg-white rounded-lg shadow p-8 text-center">
                        <h1 class="text-2xl font-bold text-gray-800 mb-3">Aucun vote</h1>
                        <p class="text-gray-600">Aucun vote n'a été enregistré pour ce scrutin.</p>
                    </div>
                <?php else:
                    $resultats = $data_resultats['resultats'];
                    $premier = $resultats[0];
                    $gagnant = $premier['candidat'];
                    ?>
                    
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Résultats du scrutin</h1>
                        <p class="text-lg text-gray-600">Combattant de l'année <?php echo $scrutin_termine['annee']; ?></p>
                        <p class="text-sm text-gray-500 mt-1">
                            Vote cloturé le <?php echo date('d/m/Y', strtotime($scrutin_termine['date_fermeture'])); ?>
                        </p>
                    </div>

                    <!-- Le gagnant -->
                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-lg shadow-lg p-6 mb-8 text-center">
                        <h2 class="text-2xl font-bold text-white mb-4">Vainqueur</h2>
                        <div class="bg-white rounded p-5">
                            <?php if (!empty($gagnant['photo_profil'])): ?>
                                <img src="<?php echo $base_url; ?>/<?php echo htmlspecialchars($gagnant['photo_profil']); ?>" 
                                     alt="<?php echo htmlspecialchars($gagnant['prenom'] . ' ' . $gagnant['nom']); ?>"
                                     class="w-40 h-40 object-cover rounded-full mx-auto mb-3 border-4 border-yellow-500">
                            <?php endif; ?>
                            <h3 class="text-2xl font-bold text-gray-900 mb-1">
                                <?php echo htmlspecialchars($gagnant['prenom'] . ' ' . $gagnant['nom']); ?>
                            </h3>
                            <p class="text-lg text-gray-700 mb-3"><?php echo htmlspecialchars($gagnant['surnom']); ?></p>
                            <div class="mt-3">
                                <span class="bg-yellow-500 text-white px-5 py-2 rounded-full text-xl font-bold">
                                    <?php echo number_format($premier['score_pondere'], 2); ?> points
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Classement -->
                    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                        <div class="bg-red-600 text-white p-3">
                            <h2 class="text-xl font-bold">Classement complet</h2>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Position</th>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Combattant</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Score</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <?php foreach ($resultats as $index => $resultat): 
                                        $position = $index + 1;
                                        $candidat = $resultat['candidat'];
                                    ?>
                                        <tr class="border-t hover:bg-gray-50">
                                            <td class="px-4 py-3">
                                                <span class="text-lg font-bold text-gray-900"><?php echo $position; ?></span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <?php if (!empty($candidat['photo_profil'])): ?>
                                                        <img src="<?php echo $base_url; ?>/<?php echo htmlspecialchars($candidat['photo_profil']); ?>" 
                                                             alt="<?php echo htmlspecialchars($candidat['prenom'] . ' ' . $candidat['nom']); ?>"
                                                             class="w-12 h-12 rounded-full object-cover mr-3">
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="font-medium text-gray-900">
                                                            <?php echo htmlspecialchars($candidat['prenom'] . ' ' . $candidat['nom']); ?>
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            <?php echo htmlspecialchars($candidat['surnom']); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="bg-red-600 text-white px-4 py-2 rounded font-bold text-lg">
                                                    <?php echo number_format($resultat['score_pondere'], 2); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Info ponderation -->
                    <div class="bg-blue-50 rounded p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">Système de vote</h3>
                        <p class="text-sm text-gray-700 mb-2">
                            Les votes sont pondérés pour équilibrer l'avis du public et des professionnels :
                        </p>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li>- Public : 20% du score final</li>
                            <li>- Journalistes : 40% du score final</li>
                            <li>- Coachs : 40% du score final</li>
                        </ul>
                    </div>

                <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
