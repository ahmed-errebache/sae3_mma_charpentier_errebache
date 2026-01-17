<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['isConnected']) || !$_SESSION['isConnected'] || $_SESSION['user_type'] !== 'administrateur') {
    header('Location: ../pages/login.php');
    exit();
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'generer_code') {
        $email = trim($_POST['email']);
        $prenom = trim($_POST['prenom']);
        $nom = trim($_POST['nom']);
        $type = $_POST['type_professionnel'];
        
        if (empty($email) || empty($prenom) || empty($nom) || empty($type)) {
            $error = 'Tous les champs sont obligatoires.';
        } else {
            // Récupérer l'ID du collège basé sur le type
            $conn = dbconnect();
            $sqlCollege = "SELECT ID_college FROM college WHERE type = :type";
            $stmtCollege = $conn->prepare($sqlCollege);
            $stmtCollege->execute([':type' => $type]);
            $college = $stmtCollege->fetch(PDO::FETCH_ASSOC);
            
            if (!$college) {
                $error = 'Collège introuvable pour ce type de professionnel.';
            } else {
                $id_college = $college['ID_college'];
                $result = creerCodeProfessionnel($email, $prenom, $nom, $type, $id_college);
            
                if ($result['success']) {
                    if (envoyerCodeProfessionnel($email, $prenom, $nom, $result['code'], $type)) {
                        $message = 'Code genere et envoye avec succes.';
                    } else {
                        $error = 'Code genere mais erreur d\'envoi de l\'email.';
                    }
                } else {
                    $error = 'Erreur lors de la generation du code.';
                }
            }
        }
    }
}

$conn = dbconnect();
$sql = "SELECT * FROM code_professionnel ORDER BY date_generation DESC";
$stmt = $conn->query($sql);
$codes = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="index.php" class="text-bleu hover:underline">&larr; Retour au tableau de bord</a>
    </div>

    <h1 class="text-3xl font-bold text-noir mb-8">Generation de codes professionnels</h1>

    <?php if (!empty($message)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-noir mb-4">Generer un nouveau code</h2>
            
            <form method="POST" action="generer_codes.php" class="space-y-4">
                <input type="hidden" name="action" value="generer_code">
                
                <div>
                    <label for="type_professionnel" class="block text-sm font-medium text-gray-700 mb-1">
                        Type de professionnel
                    </label>
                    <select 
                        id="type_professionnel" 
                        name="type_professionnel" 
                        required
                        class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:ring-bleu focus:border-bleu">
                        <option value="">-- Selectionner --</option>
                        <option value="journaliste">Journaliste</option>
                        <option value="coach">Coach</option>
                    </select>
                </div>

                <div>
                    <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">
                        Prenom
                    </label>
                    <input 
                        type="text" 
                        id="prenom" 
                        name="prenom" 
                        required
                        class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:ring-bleu focus:border-bleu"
                        placeholder="Prenom">
                </div>

                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">
                        Nom
                    </label>
                    <input 
                        type="text" 
                        id="nom" 
                        name="nom" 
                        required
                        class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:ring-bleu focus:border-bleu"
                        placeholder="Nom">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required
                        class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:ring-bleu focus:border-bleu"
                        placeholder="email@exemple.com">
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-rouge text-white py-2 px-4 rounded-md hover:bg-rouge/90 transition">
                    Generer et envoyer le code
                </button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-noir mb-4">Codes generes</h2>
            
            <div class="overflow-auto max-h-[600px]">
                <?php if (empty($codes)): ?>
                    <p class="text-gray-500 text-sm">Aucun code genere pour le moment.</p>
                <?php else: ?>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-700">Type</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-700">Nom</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-700">Email</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-700">Date</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-700">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($codes as $code): ?>
                                <tr class="<?php echo $code['utilise'] ? 'bg-gray-50' : ''; ?>">
                                    <td class="px-3 py-2">
                                        <?php echo $code['type_professionnel'] === 'journaliste' ? 'Journaliste' : 'Coach'; ?>
                                    </td>
                                    <td class="px-3 py-2">
                                        <?php echo htmlspecialchars($code['prenom'] . ' ' . $code['nom']); ?>
                                    </td>
                                    <td class="px-3 py-2 text-xs">
                                        <?php echo htmlspecialchars($code['email']); ?>
                                    </td>
                                    <td class="px-3 py-2 text-xs text-gray-600">
                                        <?php echo date('d/m/Y', strtotime($code['date_generation'])); ?>
                                    </td>
                                    <td class="px-3 py-2">
                                        <?php if ($code['utilise']): ?>
                                            <span class="text-green-600 text-xs">Utilise</span>
                                        <?php else: ?>
                                            <span class="text-orange-600 text-xs">En attente</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="font-bold text-noir mb-2">Informations</h3>
        <ul class="text-sm text-gray-700 space-y-1">
            <li>Les codes generes sont uniques et a usage unique</li>
            <li>Chaque code est envoye automatiquement par email au professionnel</li>
            <li>Les journalistes ont un poids de 40% dans le vote</li>
            <li>Les coachs ont un poids de 40% dans le vote</li>
            <li>Le public a un poids de 20% dans le vote</li>
        </ul>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
