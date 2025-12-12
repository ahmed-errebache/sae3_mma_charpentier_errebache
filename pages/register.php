<?php
session_start();
require_once '../includes/config.php'; 
require_once '../includes/functions.php';

$connexion = dbconnect();

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_type = $_POST['user_type'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    
    // Validation
    if (empty($user_type) || empty($email) || empty($password) || empty($confirm_password) || empty($nom) || empty($prenom)) {
        $error = 'Veuillez remplir tous les champs.';
    } elseif ($user_type !== 'electeur_public') {
        $error = 'Seuls les électeurs publics peuvent s\'inscrire directement.';
    } elseif ($password !== $confirm_password) {
        $error = 'Les mots de passe ne correspondent pas.';
    } elseif (strlen($password) < 6) {
        $error = 'Le mot de passe doit contenir au moins 6 caractères.';
    } else {
        // Vérifier si l'email existe déjà
        $checkSql = "SELECT COUNT(*) FROM electeur WHERE email = :email";
        $checkStmt = $connexion->prepare($checkSql);
        $checkStmt->execute([':email' => $email]);
        
        if ($checkStmt->fetchColumn() > 0) {
            $error = "Cet email est déjà utilisé.";
        } else {
            // Hasher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            try {
                $sql = "INSERT INTO electeur (email, mot_de_passe, nom, prenom, id_college) 
                        VALUES (:email, :password, :nom, :prenom, 1)";
                $stmt = $connexion->prepare($sql);
                $stmt->execute([
                    ':email' => $email,
                    ':password' => $hashedPassword,
                    ':nom' => $nom,
                    ':prenom' => $prenom
                ]);
                
                $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            } catch (Exception $e) {
                $error = "Une erreur est survenue lors de l'inscription.";
            }
        }
    }
}

require_once '../includes/header.php';
?>

<main class="flex-1">
    <div class="min-h-[70vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-6 sm:p-8">
            <h1 class="text-2xl font-bold text-center text-noir mb-6">Inscription</h1>

            <?php if (!empty($error)): ?>
                <div class="bg-error/10 border border-error text-error px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="bg-success/10 border border-success text-success px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($success); ?>
                    <a href="login.php" class="underline font-medium">Se connecter</a>
                </div>
            <?php endif; ?>

            <form action="register.php" method="post" class="space-y-5">
                <input type="hidden" name="user_type" value="electeur_public">

                <!-- Nom et Prénom -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                        <input type="text" id="nom" name="nom" required
                            class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu"
                            placeholder="Dupont">
                    </div>
                    <div>
                        <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                        <input type="text" id="prenom" name="prenom" required
                            class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu"
                            placeholder="Jean">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse e-mail</label>
                    <input type="email" id="email" name="email" required
                        class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu"
                        placeholder="email@example.com">
                </div>

                <!-- Mot de passe -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <input type="password" id="password" name="password" required
                        class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu"
                        placeholder="••••••••">
                    <p class="text-xs text-gray-500 mt-1">Au moins 6 caractères</p>
                </div>

                <!-- Confirmer mot de passe -->
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" required
                        class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu"
                        placeholder="••••••••">
                </div>

                <!-- Bouton inscription -->
                <div>
                    <button type="submit"
                        class="w-full inline-flex justify-center items-center px-4 py-2 rounded-md bg-rouge text-white font-medium shadow hover:bg-rouge/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rouge">
                        S'inscrire
                    </button>
                </div>

                <!-- Lien vers connexion -->
                <div class="text-center text-sm text-gray-600">
                    Déjà inscrit ? <a href="login.php" class="text-bleu hover:underline font-medium">Se connecter</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>