<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/config.php';

$action = $_GET['action'] ?? 'finaliser';
$message = '';
$error = '';

// Traitement finalisation de compte CANDIDAT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalisation_action'])) {
    
    if ($_POST['finalisation_action'] === 'changer_mdp') {
        $email = $_POST['email'];
        $mdpProvisoire = $_POST['mdp_provisoire'];
        $nouveauMdp = $_POST['nouveau_mdp'];
        $confirmMdp = $_POST['confirm_mdp'];
        
        if ($nouveauMdp !== $confirmMdp) {
            $error = 'Les mots de passe ne correspondent pas.';
        } else {
            $candidatId = changerMotDePasseCandidat($email, $mdpProvisoire, $nouveauMdp);
            
            if ($candidatId) {
                $_SESSION['temp_candidat_id'] = $candidatId;
                $_SESSION['temp_nouveau_mdp'] = $nouveauMdp;
                header('Location: candidat.php?action=finaliser&etape=2');
                exit();
            } else {
                $error = 'Identifiants incorrects ou compte déjà vérifié.';
            }
        }
    }
    
    if ($_POST['finalisation_action'] === 'completer_profil') {
        if (!isset($_SESSION['temp_candidat_id'])) {
            header('Location: candidat.php?action=finaliser');
            exit();
        }
        
        if (completerProfilCandidat(
            $_SESSION['temp_candidat_id'],
            $_SESSION['temp_nouveau_mdp'],
            $_POST['surnom'],
            $_FILES['photo'] ?? null
        )) {
            unset($_SESSION['temp_candidat_id']);
            unset($_SESSION['temp_nouveau_mdp']);
            
            $message = 'Votre compte a été finalisé avec succès.';
            $action = 'succes';
        } else {
            $error = 'Erreur lors de la finalisation du compte.';
        }
    }
}

$etape = $_GET['etape'] ?? 1;

require_once __DIR__ . '/../includes/header.php';
?>

<?php if ($action === 'finaliser'): ?>
    
    <?php if ($etape == 1): ?>
    <!-- Étape 1: Changement de mot de passe -->
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
            <div class="bg-gray-800 text-white p-6 rounded-t-lg">
                <h2 class="text-xl font-bold">Finaliser votre compte</h2>
                <p class="text-sm mt-1">Étape 1/2 - Créez votre mot de passe</p>
            </div>

            <div class="p-8">
                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <input type="hidden" name="finalisation_action" value="changer_mdp">
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" name="email" required 
                            class="w-full px-4 py-3 border rounded focus:outline-none focus:border-gray-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Mot de passe provisoire</label>
                        <input type="password" name="mdp_provisoire" required 
                            class="w-full px-4 py-3 border rounded focus:outline-none focus:border-gray-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Nouveau mot de passe</label>
                        <input type="password" name="nouveau_mdp" required 
                            class="w-full px-4 py-3 border rounded focus:outline-none focus:border-gray-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Confirmer le mot de passe</label>
                        <input type="password" name="confirm_mdp" required 
                            class="w-full px-4 py-3 border rounded focus:outline-none focus:border-gray-500">
                    </div>

                    <button type="submit" 
                        class="w-full bg-gray-800 text-white py-3 rounded hover:bg-gray-700">
                        Suivant
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($etape == 2): ?>
    <!-- Étape 2: Compléter le profil -->
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
            <div class="bg-gray-700 text-white p-6 rounded-t-lg">
                <h2 class="text-xl font-bold">Finaliser votre compte</h2>
                <p class="text-sm mt-1">Étape 2/2 - Complétez votre profil</p>
            </div>

            <div class="p-8">
                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="finalisation_action" value="completer_profil">
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Surnom de combat</label>
                        <input type="text" name="surnom" required 
                            class="w-full px-4 py-3 border rounded focus:outline-none focus:border-gray-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Photo de profil</label>
                        <input type="file" name="photo" accept="image/*" required 
                            class="w-full px-4 py-3 border rounded focus:outline-none focus:border-gray-500">
                    </div>

                    <button type="submit" 
                        class="w-full bg-gray-800 text-white py-3 rounded hover:bg-gray-700">
                        Finaliser mon compte
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

<?php elseif ($action === 'succes'): ?>
    <!-- Page de succès -->
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full text-center">
            <div class="bg-green-100 text-green-800 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Compte finalisé !</h2>
            <p class="text-gray-600 mb-6">Vous pouvez maintenant vous connecter.</p>
            <a href="login.php" class="bg-gray-800 text-white px-6 py-3 rounded hover:bg-gray-700 inline-block">
                Se connecter
            </a>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
