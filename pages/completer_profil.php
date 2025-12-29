<?php
session_start();

if (!isset($_SESSION['code_valide'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/config.php';
require_once '../includes/functions.php';

$error = '';
$codeInfo = $_SESSION['code_valide'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dateNaissance = trim($_POST['date_naissance']);
    $sexe = $_POST['sexe'];
    $nationalite = trim($_POST['nationalite']);
    
    if (empty($dateNaissance) || empty($sexe) || empty($nationalite)) {
        $error = 'Tous les champs sont obligatoires.';
    } else {
        $age = calculerAge($dateNaissance);
        
        if ($age === false || $age < 18 || $age > 120) {
            $error = 'Vous devez avoir entre 18 et 120 ans.';
        } else {
            $result = utiliserCodeProfessionnel(
                $codeInfo['code'], 
                $_SESSION['password_temporaire'],
                $age,
                $sexe,
                $nationalite
            );
        
        if ($result['success']) {
            $_SESSION["isConnected"] = true;
            $_SESSION['email'] = $codeInfo['email'];
            $_SESSION['user_type'] = 'electeur';
            $_SESSION['id_electeur'] = $result['id_electeur'];
            $_SESSION['type_professionnel'] = $codeInfo['type_professionnel'];
            
            unset($_SESSION['code_valide']);
            unset($_SESSION['password_temporaire']);
            
            header('Location: ../index.php');
            exit;
            } else {
                $error = $result['error'];
            }
        }
    }
}

require_once '../includes/header.php';
?>

<main class="flex-1">
    <div class="min-h-[70vh] flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-6 sm:p-8">
            <h1 class="text-2xl font-bold text-center text-noir mb-2">Completer votre profil</h1>
            <p class="text-sm text-gray-600 text-center mb-6">
                Derniere etape pour activer votre compte professionnel
            </p>

            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-700">
                    <strong>Nom :</strong> <?php echo htmlspecialchars($codeInfo['prenom'] . ' ' . $codeInfo['nom']); ?>
                </p>
                <p class="text-sm text-gray-700">
                    <strong>Type :</strong> <?php echo $codeInfo['type_professionnel'] === 'journaliste' ? 'Journaliste' : 'Coach'; ?>
                </p>
            </div>

            <form action="completer_profil.php" method="post" class="space-y-6">
                <div>
                    <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-1">
                        Date de naissance *
                    </label>
                    <input
                        type="date"
                        id="date_naissance"
                        name="date_naissance"
                        required
                        max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>"
                        min="<?php echo date('Y-m-d', strtotime('-120 years')); ?>"
                        class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu">
                    <p class="text-xs text-gray-500 mt-1">Vous devez avoir au moins 18 ans</p>
                </div>

                <div>
                    <label for="sexe" class="block text-sm font-medium text-gray-700 mb-1">
                        Sexe *
                    </label>
                    <select
                        id="sexe"
                        name="sexe"
                        required
                        class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu">
                        <option value="">-- Selectionner --</option>
                        <option value="Homme">Homme</option>
                        <option value="Femme">Femme</option>
                    </select>
                </div>

                <div>
                    <label for="nationalite" class="block text-sm font-medium text-gray-700 mb-1">
                        Nationalite *
                    </label>
                    <select
                        id="nationalite"
                        name="nationalite"
                        required
                        class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu">
                        <option value="">-- Selectionner --</option>
                        <?php foreach (getListePays() as $pays): ?>
                            <option value="<?php echo htmlspecialchars($pays); ?>">
                                <?php echo htmlspecialchars($pays); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <button
                        type="submit"
                        class="w-full inline-flex justify-center items-center px-4 py-2 rounded-md bg-rouge text-white font-medium shadow hover:bg-rouge/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rouge">
                        Valider mon compte
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
