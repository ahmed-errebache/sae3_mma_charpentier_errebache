<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['isConnected']) || $_SESSION['user_type'] !== 'administrateur') {
    header('Location: /sae3_mma_charpentier_errebache/pages/login.php');
    exit;
}

$conn = dbconnect();
$baseUrl = '/sae3_mma_charpentier_errebache';

$scrutin_id = isset($_GET['scrutin']) ? (int)$_GET['scrutin'] : null;

if (!$scrutin_id) {
    $sqlScrutin = "SELECT * FROM scrutin WHERE phase = 'resultat' ORDER BY annee DESC LIMIT 1";
    $stmtScrutin = $conn->query($sqlScrutin);
    $scrutin_actuel = $stmtScrutin->fetch(PDO::FETCH_ASSOC);
    
    if ($scrutin_actuel) {
        $scrutin_id = $scrutin_actuel['ID_scrutin'];
    }
}

$sqlScrutins = "SELECT * FROM scrutin ORDER BY annee DESC";
$stmtScrutins = $conn->query($sqlScrutins);
$tous_scrutins = $stmtScrutins->fetchAll(PDO::FETCH_ASSOC);

$resultats_data = null;
$scrutin_selectionne = null;

if ($scrutin_id) {
    $sqlScrutin = "SELECT * FROM scrutin WHERE ID_scrutin = :id";
    $stmtScrutin = $conn->prepare($sqlScrutin);
    $stmtScrutin->execute([':id' => $scrutin_id]);
    $scrutin_selectionne = $stmtScrutin->fetch(PDO::FETCH_ASSOC);
    
    if ($scrutin_selectionne) {
        $resultats_data = calculerResultatsScrutin($scrutin_id);
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-bleu to-bleu/80 px-6 py-8">
            <h1 class="text-3xl font-bebas text-white tracking-wide">Resultats du Scrutin</h1>
            <p class="text-white/90 mt-2">Calcul des resultats avec ponderation des votes par college</p>
        </div>

        <div class="p-6">
            <div class="mb-6">
                <label for="scrutin_select" class="block text-sm font-medium text-gray-700 mb-2">Selectionner un scrutin :</label>
                <select id="scrutin_select" onchange="window.location.href='?scrutin='+this.value" 
                        class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bleu focus:border-bleu">
                    <option value="">-- Choisir un scrutin --</option>
                    <?php foreach ($tous_scrutins as $s): ?>
                        <option value="<?php echo $s['ID_scrutin']; ?>" <?php echo ($scrutin_id == $s['ID_scrutin']) ? 'selected' : ''; ?>>
                            Scrutin <?php echo $s['annee']; ?> (<?php echo ucfirst($s['phase']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if (!$resultats_data): ?>
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">Selectionnez un scrutin pour voir les resultats.</p>
                </div>
            <?php else: ?>
                
                <div class="mb-8 bg-gris-clair p-6 rounded-lg">
                    <h2 class="text-2xl font-bebas text-noir mb-4">Information du scrutin</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="font-semibold">Annee :</span> <?php echo $scrutin_selectionne['annee']; ?>
                        </div>
                        <div>
                            <span class="font-semibold">Date ouverture :</span> <?php echo date('d/m/Y', strtotime($scrutin_selectionne['date_ouverture'])); ?>
                        </div>
                        <div>
                            <span class="font-semibold">Date fermeture :</span> <?php echo date('d/m/Y', strtotime($scrutin_selectionne['date_fermeture'])); ?>
                        </div>
                        <div>
                            <span class="font-semibold">Phase :</span> 
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                <?php echo $scrutin_selectionne['phase'] === 'resultat' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                <?php echo ucfirst($scrutin_selectionne['phase']); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mb-8 bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-2xl font-bebas text-noir mb-4">Statistiques de participation</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="text-sm text-gray-600">Total des votes</div>
                            <div class="text-3xl font-bebas text-noir">
                                <?php 
                                $total = ($resultats_data['totaux_colleges']['public'] ?? 0) + 
                                         ($resultats_data['totaux_colleges']['journaliste'] ?? 0) + 
                                         ($resultats_data['totaux_colleges']['coach'] ?? 0);
                                echo $total;
                                ?>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="text-sm text-gray-600">Votes Public (20%)</div>
                            <div class="text-3xl font-bebas text-noir"><?php echo $resultats_data['totaux_colleges']['public'] ?? 0; ?></div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="text-sm text-gray-600">Votes Journalistes (40%)</div>
                            <div class="text-3xl font-bebas text-noir"><?php echo $resultats_data['totaux_colleges']['journaliste'] ?? 0; ?></div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="text-sm text-gray-600">Votes Coachs (40%)</div>
                            <div class="text-3xl font-bebas text-noir"><?php echo $resultats_data['totaux_colleges']['coach'] ?? 0; ?></div>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bebas text-noir mb-4">Classement des candidats</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rang</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Candidat</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Public</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Journalistes</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Coachs</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total votes</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Score pondere</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php 
                                $rang = 1;
                                foreach ($resultats_data['resultats'] as $resultat): 
                                ?>
                                    <tr class="<?php echo $rang === 1 ? 'bg-yellow-50' : ''; ?>">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-2xl font-bebas <?php echo $rang === 1 ? 'text-dore' : 'text-gray-500'; ?>">
                                                <?php echo $rang; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo htmlspecialchars($resultat['candidat']['prenom'] . ' ' . $resultat['candidat']['nom']); ?>
                                                    </div>
                                                    <?php if ($resultat['candidat']['surnom']): ?>
                                                        <div class="text-sm text-gray-500">"<?php echo htmlspecialchars($resultat['candidat']['surnom']); ?>"</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="text-sm text-gray-900"><?php echo $resultat['nb_votes_public']; ?> votes</div>
                                            <div class="text-xs text-gray-500"><?php echo $resultat['pourcentage_public']; ?>%</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="text-sm text-gray-900"><?php echo $resultat['nb_votes_journaliste']; ?> votes</div>
                                            <div class="text-xs text-gray-500"><?php echo $resultat['pourcentage_journaliste']; ?>%</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="text-sm text-gray-900"><?php echo $resultat['nb_votes_coach']; ?> votes</div>
                                            <div class="text-xs text-gray-500"><?php echo $resultat['pourcentage_coach']; ?>%</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="text-sm font-medium text-gray-900"><?php echo $resultat['nb_votes_total']; ?></div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="text-lg font-bebas <?php echo $rang === 1 ? 'text-dore' : 'text-bleu'; ?>">
                                                <?php echo $resultat['score_pondere']; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php 
                                $rang++;
                                endforeach; 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-blue-900 mb-2">Comment le score pondere est calcule :</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>1. On calcule le pourcentage de votes recus par chaque candidat dans chaque college</li>
                        <li>2. On applique la ponderation : Public 20%, Journalistes 40%, Coachs 40%</li>
                        <li>3. Le score final est la somme ponderee des pourcentages</li>
                        <li>4. Le candidat avec le score le plus eleve remporte l'election</li>
                    </ul>
                </div>

            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
