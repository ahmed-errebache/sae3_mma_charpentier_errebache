<?php

session_start();

include '../includes/config.php'; 

$connexion = dbconnect();

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $user_type = $_POST['user_type'] ?? '';
    $email     = $_POST['email'] ?? '';
    $password  = $_POST['password'] ?? '';

    // Validation simple
    if (empty($user_type) || empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {

        // Les clés correspondent aux value des boutons radio
        $tables = [
            'electeur_public' => 'electeur',
            'electeur_pro'    => 'electeur',
            'candidat'        => 'candidat',
            'admin'           => 'administrateur'
        ];

        // Vérifier que le type est autorisé
        if (!isset($tables[$user_type])) {
            $error = "Type d'utilisateur invalide.";
        } else {
            $table = $tables[$user_type]; // <--- on utilise bien $user_type


            $sql = "SELECT COUNT(*) 
                    FROM $table 
                    WHERE email = :email 
                    AND mot_de_passe = :password";

            $stmt = $connexion->prepare($sql);
            $stmt->execute([
                ':email'    => $email,
                ':password' => $password
            ]);

            $row_count = $stmt->fetchColumn();

            if ($row_count == 1) {
                $_SESSION["isConnected"] = true;
                $_SESSION['email']       = $email;
                $_SESSION['user_type']   = $table;

                // Redirection (à adapter)
                header('Location: ../index.php');
                exit;
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        }
    }
}
?>


<?php include '../includes/header.php'; ?>



<main class="flex-1">
    <div class="min-h-[70vh] flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-6 sm:p-8">
            <h1 class="text-2xl font-bold text-center text-mma-dark mb-6">Connexion</h1>

            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
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
                        <label class="flex items-center gap-2 border rounded-lg px-3 py-2 cursor-pointer hover:border-mma-blue">
                            <input type="radio" name="user_type" value="electeur_public" class="h-4 w-4 text-mma-blue" required>
                            <span class="text-sm text-gray-700">Électeur public</span>
                        </label>

                        <!-- Electeur pro -->
                        <label class="flex items-center gap-2 border rounded-lg px-3 py-2 cursor-pointer hover:border-mma-blue">
                            <input type="radio" name="user_type" value="electeur_pro" class="h-4 w-4 text-mma-blue">
                            <span class="text-sm text-gray-700">Électeur professionnel</span>
                        </label>

                        <!-- Candidat -->
                        <label class="flex items-center gap-2 border rounded-lg px-3 py-2 cursor-pointer hover:border-mma-blue">
                            <input type="radio" name="user_type" value="candidat" class="h-4 w-4 text-mma-blue">
                            <span class="text-sm text-gray-700">Candidat</span>
                        </label>

                        <!-- Admin -->
                        <label class="flex items-center gap-2 border rounded-lg px-3 py-2 cursor-pointer hover:border-mma-blue">
                            <input type="radio" name="user_type" value="admin" class="h-4 w-4 text-mma-blue">
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
                        class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-mma-blue focus:border-mma-blue"
                        placeholder="email@example.com"
                    >
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
                        class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-mma-blue focus:border-mma-blue"
                        placeholder="••••••••"
                    >
                </div>

                <!-- Bouton confirmer -->
                <div>
                    <button
                        type="submit"
                        class="w-full inline-flex justify-center items-center px-4 py-2 rounded-md bg-mma-blue text-black font-medium shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-mma-blue"
                    >
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>



<?php include '../includes/footer.php'; ?>