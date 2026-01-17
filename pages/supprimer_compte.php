<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/config.php';

// Verifier que l'utilisateur est connecte
if (!isset($_SESSION['isConnected']) || $_SESSION['isConnected'] !== true) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION['email'] ?? null;
$userType = $_SESSION['user_type'] ?? null;
$error = '';
$success = '';

// Traitement de la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $confirmation = $_POST['confirmation'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($confirmation !== 'SUPPRIMER') {
        $error = 'Vous devez taper SUPPRIMER pour confirmer.';
    } elseif (empty($password)) {
        $error = 'Veuillez entrer votre mot de passe.';
    } else {
        // Verifier le mot de passe
        $conn = dbconnect();
        $sql = "SELECT mot_de_passe FROM $userType WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user || !password_verify($password, $user['mot_de_passe'])) {
            $error = 'Mot de passe incorrect.';
        } else {
            // Supprimer le compte
            $result = supprimerCompte($email, $userType);
            
            if ($result['success']) {
                // Deconnecter l'utilisateur
                session_destroy();
                header('Location: ../index.php?compte_supprime=1');
                exit;
            } else {
                $error = $result['message'];
            }
        }
    }
}

require_once '../includes/header.php';
?>

<main class="flex-1">
    <div class="min-h-[70vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="w-full max-w-2xl bg-white rounded-xl shadow-lg p-6 sm:p-8">
            <h1 class="text-3xl font-bold text-center text-noir mb-6">Supprimer mon compte</h1>
            
            <div class="bg-rouge/10 border border-rouge rounded-lg p-4 mb-6">
                <h2 class="text-lg font-semibold text-rouge mb-2">Attention</h2>
                <p class="text-gray-700 mb-2">
                    La suppression de votre compte est definitive et irreversible.
                </p>
                <p class="text-gray-700">
                    Toutes vos informations personnelles seront supprimees de nos serveurs conformement au RGPD.
                </p>
            </div>
            
            <?php if ($error): ?>
                <div class="bg-error/10 border border-error text-error px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pour confirmer, tapez <span class="font-bold text-rouge">SUPPRIMER</span> dans le champ ci-dessous
                    </label>
                    <input 
                        type="text" 
                        name="confirmation" 
                        required
                        placeholder="SUPPRIMER"
                        class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-rouge focus:border-rouge"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mot de passe
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        required
                        placeholder="Entrez votre mot de passe"
                        class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-rouge focus:border-rouge"
                    >
                </div>
                
                <div class="flex gap-4">
                    <a 
                        href="profil.php" 
                        class="flex-1 text-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors"
                    >
                        Annuler
                    </a>
                    <button 
                        type="submit"
                        class="flex-1 px-4 py-2 bg-rouge text-white rounded-md hover:bg-rouge/80 transition-colors font-medium"
                    >
                        Supprimer mon compte
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
