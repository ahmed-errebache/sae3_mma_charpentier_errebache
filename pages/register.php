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
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Recuperer l'adresse IP de l'utilisateur
            $adresse_ip = $_SERVER['REMOTE_ADDR'];
            
            // Verifier combien de comptes existent deja avec cette IP
            $checkIpSql = "SELECT COUNT(*) FROM electeur WHERE adresse_IP = :ip AND id_college = 1";
            $checkIpStmt = $connexion->prepare($checkIpSql);
            $checkIpStmt->execute([':ip' => $adresse_ip]);
            $comptesExistants = $checkIpStmt->fetchColumn();
            
            if ($comptesExistants >= 3) {
                $error = "Vous avez atteint le nombre maximum de comptes (3 comptes).";
            } else {
                // Hasher le mot de passe
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                $_SESSION['isConnected'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['user_type'] = 'electeur';
                $_SESSION['profil_complet'] = false;
                
                header('Location: completer_profil_electeur.php');
                exit;
            } catch (Exception $e) {
                $error = "Une erreur est survenue lors de l'inscription.";
                try {
                    $sql = "INSERT INTO electeur (email, mot_de_passe, nom, prenom, id_college, adresse_IP) 
                            VALUES (:email, :password, :nom, :prenom, 1, :ip)";
                    $stmt = $connexion->prepare($sql);
                    $stmt->execute([
                        ':email' => $email,
                        ':password' => $hashedPassword,
                        ':nom' => $nom,
                        ':prenom' => $prenom,
                        ':ip' => $adresse_ip
                    ]);
                    
                    $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                } catch (Exception $e) {
                    $error = "Une erreur est survenue lors de l'inscription.";
                }
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

<?php require_once '../includes/header.php'; ?>

            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
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
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <svg id="eye-icon-password" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Au moins 6 caractères</p>
                </div>

                <!-- Confirmer mot de passe -->
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                    <div class="relative">
                        <input type="password" id="confirm_password" name="confirm_password" required
                            class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword('confirm_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <svg id="eye-icon-confirm_password" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
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

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById('eye-icon-' + fieldId);
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
    } else {
        field.type = 'password';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
    }
}

<?php if (!empty($error) && strpos($error, 'nombre maximum') !== false): ?>
window.addEventListener('load', function() {
    alert('⚠️ LIMITE ATTEINTE\n\nVous avez obtenu le nombre maximum de comptes (3 comptes).\n\nIl n\'est plus possible de creer un nouveau compte avec cette connexion Internet.');
});
<?php endif; ?>
</script>

<?php require_once '../includes/footer.php'; ?>