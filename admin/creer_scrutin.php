<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_SESSION['isConnected']) || $_SESSION['user_type'] !== 'administrateur') {
    header('Location: /sae3_mma_charpentier_errebache/pages/login.php');
    exit;
}

$conn = dbconnect();
$baseUrl = '/sae3_mma_charpentier_errebache';

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        
        if ($_POST['action'] === 'creer' && isset($_POST['annee']) && isset($_POST['date_ouverture']) && isset($_POST['date_fermeture'])) {
            $annee = (int)$_POST['annee'];
            $date_ouverture = $_POST['date_ouverture'];
            $date_fermeture = $_POST['date_fermeture'];
            
            if ($date_fermeture <= $date_ouverture) {
                $errorMessage = "La date de fermeture doit etre apres la date d'ouverture.";
            } else {
                try {
                    $sql = "INSERT INTO scrutin (annee, date_ouverture, date_fermeture, phase, id_admin) 
                            VALUES (:annee, :date_ouverture, :date_fermeture, 'preparation', :id_admin)";
                    
                    $stmt = $conn->prepare($sql);
                    $result = $stmt->execute([
                        ':annee' => $annee,
                        ':date_ouverture' => $date_ouverture,
                        ':date_fermeture' => $date_fermeture,
                        ':id_admin' => $_SESSION['user_id']
                    ]);
                    
                    if ($result) {
                        $successMessage = "Le scrutin pour l'annee $annee a ete cree avec succes.";
                    }
                } catch (PDOException $e) {
                    $errorMessage = "Erreur lors de la creation du scrutin.";
                }
            }
        }
        
        if ($_POST['action'] === 'affecter_candidats' && isset($_POST['id_scrutin']) && isset($_POST['candidats'])) {
            $id_scrutin = (int)$_POST['id_scrutin'];
            $candidats = $_POST['candidats'];
            
            try {
                $conn->beginTransaction();
                
                // Desaffecter tous les candidats du scrutin
                $sqlReset = "UPDATE candidat SET id_scrutin = NULL WHERE id_scrutin = :id_scrutin";
                $stmtReset = $conn->prepare($sqlReset);
                $stmtReset->execute([':id_scrutin' => $id_scrutin]);
                
                // Affecter les candidats selectionnes
                if (!empty($candidats)) {
                    $sqlUpdate = "UPDATE candidat SET id_scrutin = :id_scrutin WHERE ID_candidat = :id_candidat";
                    $stmtUpdate = $conn->prepare($sqlUpdate);
                    
                    foreach ($candidats as $id_candidat) {
                        $stmtUpdate->execute([
                            ':id_scrutin' => $id_scrutin,
                            ':id_candidat' => (int)$id_candidat
                        ]);
                    }
                }
                
                $conn->commit();
                $successMessage = "Les candidats ont ete affectes au scrutin avec succes.";
            } catch (PDOException $e) {
                $conn->rollBack();
                $errorMessage = "Erreur lors de l'affectation des candidats.";
            }
        }
        
        if ($_POST['action'] === 'changer_phase' && isset($_POST['id_scrutin']) && isset($_POST['nouvelle_phase'])) {
            $id_scrutin = (int)$_POST['id_scrutin'];
            $nouvelle_phase = $_POST['nouvelle_phase'];
            
            try {
                $sql = "UPDATE scrutin SET phase = :phase WHERE ID_scrutin = :id_scrutin";
                $stmt = $conn->prepare($sql);
                $result = $stmt->execute([
                    ':phase' => $nouvelle_phase,
                    ':id_scrutin' => $id_scrutin
                ]);
                
                if ($result) {
                    $successMessage = "La phase du scrutin a ete modifiee avec succes.";
                }
            } catch (PDOException $e) {
                $errorMessage = "Erreur lors de la modification.";
            }
        }
    }
}

$sqlScrutins = "SELECT * FROM scrutin ORDER BY annee DESC, date_ouverture DESC";
$stmtScrutins = $conn->query($sqlScrutins);
$scrutins = $stmtScrutins->fetchAll(PDO::FETCH_ASSOC);

// Recuperer tous les candidats verifies
$sqlCandidats = "SELECT ID_candidat, prenom, nom, surnom, id_scrutin FROM candidat WHERE compte_verifie = 1 ORDER BY nom, prenom";
$stmtCandidats = $conn->query($sqlCandidats);
$tousCandidats = $stmtCandidats->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-rouge to-rouge/80 px-6 py-8">
            <h1 class="text-3xl font-bebas text-white tracking-wide">Gestion des Scrutins</h1>
            <p class="text-white/90 mt-2">Creer et gerer les scrutins pour l'election du combattant de l'annee</p>
        </div>

        <div class="p-6">
            <?php if ($successMessage): ?>
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>

            <?php if ($errorMessage): ?>
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <?php echo htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>

            <div class="mb-8 bg-gris-clair p-6 rounded-lg">
                <h2 class="text-2xl font-bebas text-noir mb-4">Creer un nouveau scrutin</h2>
                <form method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="hidden" name="action" value="creer">
                    
                    <div>
                        <label for="annee" class="block text-sm font-medium text-gray-700 mb-2">Annee</label>
                        <input type="number" name="annee" id="annee" required min="2025" max="2100" value="<?php echo date('Y'); ?>" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rouge focus:border-rouge">
                    </div>
                    
                    <div>
                        <label for="date_ouverture" class="block text-sm font-medium text-gray-700 mb-2">Date d'ouverture</label>
                        <input type="date" name="date_ouverture" id="date_ouverture" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rouge focus:border-rouge">
                    </div>
                    
                    <div>
                        <label for="date_fermeture" class="block text-sm font-medium text-gray-700 mb-2">Date de fermeture</label>
                        <input type="date" name="date_fermeture" id="date_fermeture" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rouge focus:border-rouge">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-rouge hover:bg-rouge/80 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                            Creer le scrutin
                        </button>
                    </div>
                </form>
            </div>

            <div>
                <h2 class="text-2xl font-bebas text-noir mb-4">Scrutins existants</h2>
                
                <?php if (empty($scrutins)): ?>
                    <p class="text-gray-500 text-center py-8">Aucun scrutin n'a ete cree pour le moment.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Annee</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date ouverture</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date fermeture</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phase</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($scrutins as $scrutin): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $scrutin['ID_scrutin']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $scrutin['annee']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo date('d/m/Y', strtotime($scrutin['date_ouverture'])); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo date('d/m/Y', strtotime($scrutin['date_fermeture'])); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php 
                                                    echo $scrutin['phase'] === 'vote' ? 'bg-green-100 text-green-800' : 
                                                        ($scrutin['phase'] === 'preparation' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800'); 
                                                ?>">
                                                <?php echo ucfirst($scrutin['phase']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex gap-2">
                                                <form method="POST" class="inline-block">
                                                    <input type="hidden" name="action" value="changer_phase">
                                                    <input type="hidden" name="id_scrutin" value="<?php echo $scrutin['ID_scrutin']; ?>">
                                                    <select name="nouvelle_phase" onchange="this.form.submit()" 
                                                            class="text-sm border-gray-300 rounded-md focus:ring-rouge focus:border-rouge">
                                                        <option value="">Changer phase...</option>
                                                        <option value="preparation">Preparation</option>
                                                        <option value="vote">Vote</option>
                                                        <option value="resultat">Resultat</option>
                                                    </select>
                                                </form>
                                                <button onclick="afficherModaleCandidats(<?php echo $scrutin['ID_scrutin']; ?>)" 
                                                        class="bg-bleu hover:bg-bleu/80 text-white px-3 py-1 rounded text-xs">
                                                    Candidats
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Legende des phases :</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li><span class="font-semibold text-yellow-700">Preparation :</span> Le scrutin est en preparation, les votes ne sont pas encore ouverts</li>
                    <li><span class="font-semibold text-green-700">Vote :</span> Le scrutin est ouvert, les electeurs peuvent voter</li>
                    <li><span class="font-semibold text-blue-700">Resultat :</span> Le scrutin est cloture, les resultats sont disponibles</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modale pour affecter les candidats -->
<div id="modaleCandidats" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-hidden">
        <div class="bg-gradient-to-r from-rouge to-rouge/80 px-6 py-4">
            <h2 class="text-2xl font-bebas text-white">Affecter les candidats au scrutin</h2>
        </div>
        
        <div class="p-6 overflow-y-auto max-h-[60vh]">
            <form method="POST" id="formCandidats">
                <input type="hidden" name="action" value="affecter_candidats">
                <input type="hidden" name="id_scrutin" id="scrutin_id">
                
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-4">Selectionnez les candidats qui participeront a ce scrutin :</p>
                    
                    <div class="space-y-2">
                        <?php foreach ($tousCandidats as $candidat): ?>
                            <label class="flex items-center p-3 hover:bg-gray-50 rounded cursor-pointer border border-gray-200">
                                <input type="checkbox" 
                                       name="candidats[]" 
                                       value="<?php echo $candidat['ID_candidat']; ?>"
                                       data-candidat-id="<?php echo $candidat['ID_candidat']; ?>"
                                       data-scrutin-id="<?php echo $candidat['id_scrutin']; ?>"
                                       class="h-4 w-4 text-rouge focus:ring-rouge border-gray-300 rounded">
                                <span class="ml-3 text-sm">
                                    <span class="font-medium text-gray-900">
                                        <?php echo htmlspecialchars($candidat['prenom'] . ' ' . $candidat['nom']); ?>
                                    </span>
                                    <?php if ($candidat['surnom']): ?>
                                        <span class="text-gray-500">"<?php echo htmlspecialchars($candidat['surnom']); ?>"</span>
                                    <?php endif; ?>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="fermerModaleCandidats()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-rouge hover:bg-rouge/80 text-white rounded-lg">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function afficherModaleCandidats(scrutinId) {
    document.getElementById('scrutin_id').value = scrutinId;
    
    // Decocher toutes les cases
    document.querySelectorAll('input[name="candidats[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Cocher les candidats deja affectes a ce scrutin
    document.querySelectorAll('input[name="candidats[]"]').forEach(checkbox => {
        const candidatScrutinId = checkbox.getAttribute('data-scrutin-id');
        if (candidatScrutinId == scrutinId) {
            checkbox.checked = true;
        }
    });
    
    document.getElementById('modaleCandidats').classList.remove('hidden');
}

function fermerModaleCandidats() {
    document.getElementById('modaleCandidats').classList.add('hidden');
}

// Fermer la modale en cliquant en dehors
document.getElementById('modaleCandidats').addEventListener('click', function(e) {
    if (e.target === this) {
        fermerModaleCandidats();
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
