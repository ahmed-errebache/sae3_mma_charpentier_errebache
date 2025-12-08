<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Vérifier si admin connecté
if (!isset($_SESSION['isConnected']) || !$_SESSION['isConnected'] || $_SESSION['user_type'] !== 'administrateur') {
    header('Location: ../pages/login.php');
    exit();
}

$message = '';
$error = '';

// Traitement des actions ADMIN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['action']) && $_POST['action'] === 'ajouter_candidat') {
        $palmares = array_filter($_POST['palmares']);
        
        $data = [
            'email' => $_POST['email'],
            'nom' => $_POST['nom'],
            'prenom' => $_POST['prenom'],
            'nationalite' => $_POST['nationalite'],
            'pays_origine' => $_POST['pays_origine'],
            'palmares' => $palmares
        ];
        
        $result = creerCandidat($data);
        
        if ($result['success']) {
            $message = 'Candidat créé avec succès. Email envoyé.';
        } else {
            $error = $result['error'];
        }
    }
    
    if (isset($_POST['action']) && $_POST['action'] === 'modifier_candidat') {
        $palmares = array_filter($_POST['palmares']);
        
        $data = [
            'nom' => $_POST['nom'],
            'prenom' => $_POST['prenom'],
            'nationalite' => $_POST['nationalite'],
            'pays_origine' => $_POST['pays_origine'],
            'palmares' => $palmares
        ];
        
        if (modifierCandidatAdmin($_POST['id_candidat'], $data)) {
            $message = 'Candidat modifié avec succès.';
        } else {
            $error = 'Erreur lors de la modification.';
        }
    }
    
    if (isset($_POST['action']) && $_POST['action'] === 'toggle_actif') {
        if (toggleCompteActif($_POST['id_candidat'])) {
            $message = 'Statut du compte modifié.';
        } else {
            $error = 'Erreur lors de la modification du statut.';
        }
    }
    
    if (isset($_POST['action']) && $_POST['action'] === 'supprimer_candidat') {
        if (supprimerCandidat($_POST['id_candidat'])) {
            $message = 'Candidat supprimé avec succès.';
        } else {
            $error = 'Erreur lors de la suppression.';
        }
    }
}

$candidats = getTousCandidats();
$pays = getListePays();

$candidatEdit = null;
if (isset($_GET['edit'])) {
    $candidatEdit = getCandidatById($_GET['edit']);
    if ($candidatEdit) {
        $candidatEdit['palmares_array'] = json_decode($candidatEdit['palmares'], true) ?: [];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Gestion des Candidats</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des Candidats</h1>
    </div>

    <?php if ($message): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire Ajouter/Modifier Candidat -->
    <div class="bg-white rounded-lg shadow p-8 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-8">
            <?php echo $candidatEdit ? 'Modifier le candidat' : 'Ajouter un candidat'; ?>
        </h2>
        
        <form method="POST" class="space-y-8">
            <input type="hidden" name="action" value="<?php echo $candidatEdit ? 'modifier_candidat' : 'ajouter_candidat'; ?>">
            <?php if ($candidatEdit): ?>
                <input type="hidden" name="id_candidat" value="<?php echo $candidatEdit['ID_candidat']; ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-gray-700 font-medium mb-3">Nom de famille</label>
                    <input type="text" name="nom" required 
                        value="<?php echo $candidatEdit ? htmlspecialchars($candidatEdit['nom']) : ''; ?>"
                        class="w-full px-4 py-3 border rounded focus:outline-none focus:border-gray-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-3">Prénom</label>
                    <input type="text" name="prenom" required 
                        value="<?php echo $candidatEdit ? htmlspecialchars($candidatEdit['prenom']) : ''; ?>"
                        class="w-full px-4 py-3 border rounded focus:outline-none focus:border-gray-500">
                </div>
            </div>

            <?php if (!$candidatEdit): ?>
            <div>
                <label class="block text-gray-700 font-medium mb-3">Email</label>
                <input type="email" name="email" required 
                    class="w-full px-4 py-3 border rounded focus:outline-none focus:border-gray-500">
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-gray-700 font-medium mb-3">Nationalité</label>
                    <select name="nationalite" required 
                        class="w-full px-4 py-3 border rounded focus:outline-none focus:border-gray-500">
                        <option value="">Sélectionner...</option>
                        <?php foreach ($pays as $p): ?>
                            <option value="<?php echo htmlspecialchars($p); ?>"
                                <?php echo ($candidatEdit && $candidatEdit['nationalite'] === $p) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($p); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-3">Pays d'origine</label>
                    <select name="pays_origine" required 
                        class="w-full px-4 py-3 border rounded focus:outline-none focus:border-gray-500">
                        <option value="">Sélectionner...</option>
                        <?php foreach ($pays as $p): ?>
                            <option value="<?php echo htmlspecialchars($p); ?>"
                                <?php echo ($candidatEdit && $candidatEdit['pays_origine'] === $p) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($p); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-3">Palmarès 2025</label>
                <div id="palmares-container" class="space-y-4">
                    <?php
                    $palmaresArray = $candidatEdit ? $candidatEdit['palmares_array'] : [''];
                    foreach ($palmaresArray as $palmares):
                    ?>
                    <input type="text" name="palmares[]" 
                        value="<?php echo htmlspecialchars($palmares); ?>"
                        placeholder="Ex: Champion UFC 2025"
                        class="w-full px-4 py-3 border rounded focus:outline-none focus:border-gray-500">
                    <?php endforeach; ?>
                </div>
                <button type="button" onclick="ajouterPalmares()" 
                    class="mt-4 text-gray-700 hover:text-gray-900 font-medium">
                    + Ajouter une ligne
                </button>
            </div>

            <div class="flex gap-4">
                <button type="submit" 
                    class="bg-gray-800 text-white px-8 py-3 rounded hover:bg-gray-700">
                    <?php echo $candidatEdit ? 'Modifier' : 'Créer le candidat'; ?>
                </button>
                <?php if ($candidatEdit): ?>
                    <a href="index.php" 
                        class="bg-gray-300 text-gray-800 px-8 py-3 rounded hover:bg-gray-400 inline-block">
                        Annuler
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Liste des candidats (Admin) -->
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Liste des candidats</h2>
        
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-3 px-4">Nom</th>
                    <th class="text-left py-3 px-4">Email</th>
                    <th class="text-left py-3 px-4">Statut</th>
                    <th class="text-left py-3 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($candidats as $c): ?>
                <tr class="border-b">
                    <td class="py-3 px-4"><?php echo htmlspecialchars($c['prenom'] . ' ' . $c['nom']); ?></td>
                    <td class="py-3 px-4"><?php echo htmlspecialchars($c['email']); ?></td>
                    <td class="py-3 px-4">
                        <?php if ($c['compte_verifie']): ?>
                            <span class="text-green-600">Vérifié</span>
                        <?php else: ?>
                            <span class="text-yellow-600">Non vérifié</span>
                        <?php endif; ?>
                        
                        <?php if (!$c['compte_actif']): ?>
                            <span class="text-red-600 ml-2">Désactivé</span>
                        <?php endif; ?>
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex gap-3">
                            <a href="?edit=<?php echo $c['ID_candidat']; ?>" 
                                class="text-blue-600 hover:text-blue-800">Modifier</a>
                            
                            <form method="POST" class="inline">
                                <input type="hidden" name="action" value="toggle_actif">
                                <input type="hidden" name="id_candidat" value="<?php echo $c['ID_candidat']; ?>">
                                <button type="submit" class="text-orange-600 hover:text-orange-800">
                                    <?php echo $c['compte_actif'] ? 'Désactiver' : 'Activer'; ?>
                                </button>
                            </form>
                            
                            <form method="POST" class="inline" 
                                onsubmit="return confirm('Confirmer la suppression ?');">
                                <input type="hidden" name="action" value="supprimer_candidat">
                                <input type="hidden" name="id_candidat" value="<?php echo $c['ID_candidat']; ?>">
                                <button type="submit" class="text-red-600 hover:text-red-800">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function ajouterPalmares() {
    const container = document.getElementById('palmares-container');
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'palmares[]';
    input.placeholder = 'Ex: Champion UFC 2025';
    input.className = 'w-full px-4 py-3 border rounded focus:outline-none focus:border-gray-500';
    container.appendChild(input);
}
</script>

</body>
</html>
