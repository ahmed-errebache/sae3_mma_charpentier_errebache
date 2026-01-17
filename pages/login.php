<?php
session_start();

// Rediriger si déjà connecté
if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) {
    header('Location: /sae3_mma_charpentier_errebache/index.php');
    exit;
}

require_once '../includes/config.php'; 
require_once '../includes/config.php';

$connexion = dbconnect();

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connexion_type = $_POST['connexion_type'] ?? 'normal';
    
    if ($connexion_type === 'code') {
        $code = trim($_POST['code'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($code) || empty($password)) {
            $error = 'Veuillez remplir tous les champs.';
        } elseif (strlen($password) < 8) {
            $error = 'Le mot de passe doit contenir au moins 8 caracteres.';
        } else {
            $codeInfo = verifierCodeUnique($code);
            
            if (!$codeInfo) {
                $error = 'Code invalide ou deja utilise.';
            } else {
                $_SESSION['code_valide'] = $codeInfo;
                $_SESSION['password_temporaire'] = $password;
                
                header('Location: completer_profil.php');
                exit;
            }
        }
    } else {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error = 'Veuillez remplir tous les champs.';
        } else {
            $tables = ['administrateur', 'candidat', 'electeur'];
            $user = null;
            $user_table = null;

            foreach ($tables as $table) {
                $sql = "SELECT * FROM $table WHERE email = :email";
                $stmt = $connexion->prepare($sql);
                $stmt->execute([':email' => $email]);
                $found_user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($found_user && password_verify($password, $found_user['mot_de_passe'])) {
                    $user = $found_user;
                    $user_table = $table;
                    break;
                }
            }

            if ($user) {
                if ($user_table === 'candidat' && isset($user['compte_verifie']) && $user['compte_verifie'] == 0) {
                    $error = "Veuillez finaliser votre compte avant de vous connecter.";
                } else {
                    $_SESSION["isConnected"] = true;
                    $_SESSION['email'] = $email;
                    $_SESSION['user_type'] = $user_table;

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
                <div class="bg-error/10 border border-error text-error px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="flex border-b border-gray-200 mb-6">
                <button type="button" onclick="switchTab('normal')" id="tab-normal" class="flex-1 py-3 text-center border-b-2 border-bleu text-bleu font-medium">
                    Connexion Standard
                </button>
                <button type="button" onclick="switchTab('code')" id="tab-code" class="flex-1 py-3 text-center border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                    Connexion Professionnelle
                </button>
            </div>

            <form action="login.php" method="post" id="form-normal" class="space-y-6">
                <input type="hidden" name="connexion_type" value="normal">
                
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
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <svg id="eye-icon-password" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
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

            <form action="login.php" method="post" id="form-code" class="space-y-6 hidden">
                <input type="hidden" name="connexion_type" value="code">
                
                <p class="text-sm text-gray-600 mb-4">
                    Entrez le code unique qui vous a ete envoye par email.
                </p>

                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                        Code d'acces
                    </label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu uppercase"
                        placeholder="XXXXXXXXXXXX"
                        style="letter-spacing: 2px;">
                </div>

                <div>
                    <label for="password-code" class="block text-sm font-medium text-gray-700 mb-1">
                        Choisissez un mot de passe
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password-code"
                            name="password"
                            class="block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password-code')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <svg id="eye-icon-password-code" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Minimum 8 caracteres</p>
                </div>

                <div>
                    <button
                        type="submit"
                        class="w-full inline-flex justify-center items-center px-4 py-2 rounded-md bg-rouge text-white font-medium shadow hover:bg-rouge/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rouge">
                        Valider le code
                    </button>
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

function switchTab(type) {
    const normalTab = document.getElementById('tab-normal');
    const codeTab = document.getElementById('tab-code');
    const normalForm = document.getElementById('form-normal');
    const codeForm = document.getElementById('form-code');
    
    if (type === 'normal') {
        normalTab.classList.add('border-bleu', 'text-bleu');
        normalTab.classList.remove('border-transparent', 'text-gray-500');
        codeTab.classList.remove('border-bleu', 'text-bleu');
        codeTab.classList.add('border-transparent', 'text-gray-500');
        normalForm.classList.remove('hidden');
        codeForm.classList.add('hidden');
    } else {
        codeTab.classList.add('border-bleu', 'text-bleu');
        codeTab.classList.remove('border-transparent', 'text-gray-500');
        normalTab.classList.remove('border-bleu', 'text-bleu');
        normalTab.classList.add('border-transparent', 'text-gray-500');
        codeForm.classList.remove('hidden');
        normalForm.classList.add('hidden');
    }
}
</script>



<?php require_once '../includes/footer.php'; ?>