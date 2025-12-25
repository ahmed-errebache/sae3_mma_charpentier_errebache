<?php
session_start();

// Rediriger si déjà connecté
if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) {
    header('Location: /sae3_mma_charpentier_errebache/index.php');
    exit;
}

require_once '../includes/config.php'; 

$connexion = dbconnect();

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_type = $_POST['user_type'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($user_type) || empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        $tables = [
            'electeur_public' => 'electeur',
            'electeur_pro' => 'electeur',
            'candidat' => 'candidat',
            'admin' => 'administrateur'
        ];

        if (!isset($tables[$user_type])) {
            $error = "Type d'utilisateur invalide.";
        } else {
            $table = $tables[$user_type];

            // Récupérer l'utilisateur par email
            $sql = "SELECT * FROM $table WHERE email = :email";
            $stmt = $connexion->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier si l'utilisateur existe et si le mot de passe correspond
            if ($user && password_verify($password, $user['mot_de_passe'])) {
                // Pour les candidats, vérifier que le compte est vérifié
                if ($user_type === 'candidat' && isset($user['compte_verifie']) && $user['compte_verifie'] == 0) {
                    $error = "Veuillez finaliser votre compte avant de vous connecter.";
                } else {
                    $_SESSION["isConnected"] = true;
                    $_SESSION['email']       = $email;
                    $_SESSION['user_type']   = $table;

                    // Redirection vers la page d'accueil
                    header('Location: ../index.php');
                    exit;
                }
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        }
    }
}

require_once '../includes/header.php';
?>


<?php require_once '../includes/header.php'; ?>



<main class="flex-1">
    <div class="min-h-[70vh] flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-6 sm:p-8">
            <h1 class="text-2xl font-bold text-center text-noir mb-6">Connexion</h1>

            <?php if (!empty($error)): ?>
                <div class="bg-error/10 border border-error text-error px-4 py-3 rounded">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="post" class="space-y-6">
                <!-- Type d'utilisateur -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Vous êtes :
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Electeur public -->
                        <label class="flex items-center gap-2 border rounded-lg px-3 py-2 cursor-pointer hover:border-bleu">
                            <input type="radio" name="user_type" value="electeur_public" class="h-4 w-4 text-bleu" required>
                            <span class="text-sm text-gray-700">Électeur public</span>
                        </label>

                        <!-- Electeur professionnel -->
                        <label class="flex items-center gap-2 border rounded-lg px-3 py-2 cursor-pointer hover:border-bleu">
                            <input type="radio" name="user_type" value="electeur_pro" class="h-4 w-4 text-bleu">
                            <span class="text-sm text-gray-700">Électeur professionnel</span>
                        </label>

                        <!-- Candidat -->
                        <label class="flex items-center gap-2 border rounded-lg px-3 py-2 cursor-pointer hover:border-bleu">
                            <input type="radio" name="user_type" value="candidat" class="h-4 w-4 text-bleu">
                            <span class="text-sm text-gray-700">Candidat</span>
                        </label>

                        <!-- Administrateur -->
                        <label class="flex items-center gap-2 border rounded-lg px-3 py-2 cursor-pointer hover:border-bleu">
                            <input type="radio" name="user_type" value="admin" class="h-4 w-4 text-bleu">
                            <span class="text-sm text-gray-700">Administrateur</span>
                        </label>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Adresse e-mail
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu"
                        placeholder="email@example.com">
                </div>

                <!-- Mot de passe -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Mot de passe
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu"
                        placeholder="••••••••">
                </div>

                <!-- Bouton se connecter -->
                <div>
                    <button
                        type="submit"
                        class="w-full inline-flex justify-center items-center px-4 py-2 rounded-md bg-rouge text-white font-medium shadow hover:bg-rouge/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rouge">
                        Se connecter
                    </button>
                </div>

                <!-- Lien vers inscription -->
                <div class="text-center text-sm text-gray-600">
                    Pas encore inscrit ? <a href="register.php" class="text-bleu hover:underline font-medium">S'inscrire</a>
                </div>
            </form>
        </div>
    </div>
</main>



<?php require_once '../includes/footer.php'; ?>