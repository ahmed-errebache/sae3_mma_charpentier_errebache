<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Déconnexion avec le bouton
if (isset($_GET['action']) && $_GET['action'] === 'logout') {

    // Suppression des variables de session
    unset($_SESSION['isConnected'], $_SESSION['email'], $_SESSION['user_type']);

    // Redirection vers l'accueil
    header('Location: ../index.php');
    exit;
}


require_once '../includes/config.php';

// Revérification que l'utilisateur est connecté
if (!isset($_SESSION['isConnected']) || $_SESSION['isConnected'] !== true) {
    header('Location: login.php');
    exit;
}

$connexion = dbconnect();
$email = $_SESSION['email'] ?? null;
$userType = $_SESSION['user_type'] ?? null;

if (!$email || !$userType) {
    header('Location: login.php');
    exit;
}

$errors = [];
$success = '';

// Recuperation initiale des donnees utilisateur
try {
    $sql = "SELECT * FROM $userType WHERE email = :email";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        header('Location: login.php');
        exit;
    }
} catch (PDOException $e) {
    echo "Erreur SQL: " . $e->getMessage();
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if ($userType === 'administrateur') {
        // Admin peut modifier ses infos sauf l'email
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $currentPassword = trim($_POST['current_password'] ?? '');
        $newPassword = trim($_POST['new_password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');
        
        if (empty($nom) || empty($prenom)) {
            $errors[] = "Le nom et prénom sont obligatoires.";
        }
        
        // Si on veut changer le mot de passe
        if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
            if (empty($currentPassword)) {
                $errors[] = "Veuillez entrer votre mot de passe actuel.";
            } elseif (!password_verify($currentPassword, $user['mot_de_passe'])) {
                $errors[] = "Le mot de passe actuel est incorrect.";
            } elseif (empty($newPassword)) {
                $errors[] = "Veuillez entrer un nouveau mot de passe.";
            } elseif ($newPassword !== $confirmPassword) {
                $errors[] = "Les nouveaux mots de passe ne correspondent pas.";
            }
        }
        
        if (empty($errors)) {
            try {
                $sql = "UPDATE administrateur SET nom = :nom, prenom = :prenom";
                $params = [':nom' => $nom, ':prenom' => $prenom, ':email' => $email];
                
                // Si nouveau mot de passe validé
                if (!empty($newPassword) && !empty($currentPassword)) {
                    $sql .= ", mot_de_passe = :password";
                    $params[':password'] = password_hash($newPassword, PASSWORD_DEFAULT);
                }
                
                $sql .= " WHERE email = :email";
                $stmt = $connexion->prepare($sql);
                $stmt->execute($params);
                
                $success = "Informations mises à jour avec succès.";
                
                // Rafraichir les donnees
                $stmt = $connexion->prepare("SELECT * FROM administrateur WHERE email = :email");
                $stmt->execute([':email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $errors[] = "Erreur lors de la mise à jour.";
            }
        }
        
    } elseif ($userType === 'electeur') {
        // Electeur peut modifier uniquement nom et prenom
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $currentPassword = trim($_POST['current_password'] ?? '');
        $newPassword = trim($_POST['new_password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');
        
        if (empty($nom) || empty($prenom)) {
            $errors[] = "Le nom et prenom sont obligatoires.";
        }
        
        // Si on veut changer le mot de passe
        if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
            if (empty($currentPassword)) {
                $errors[] = "Veuillez entrer votre mot de passe actuel.";
            } elseif (!password_verify($currentPassword, $user['mot_de_passe'])) {
                $errors[] = "Le mot de passe actuel est incorrect.";
            } elseif (empty($newPassword)) {
                $errors[] = "Veuillez entrer un nouveau mot de passe.";
            } elseif (!validerMotDePasse($newPassword)) {
                $errors[] = "Le mot de passe doit contenir au moins 8 caracteres, une majuscule et un chiffre.";
            } elseif ($newPassword !== $confirmPassword) {
                $errors[] = "Les nouveaux mots de passe ne correspondent pas.";
            }
        }
        
        if (empty($errors)) {
            try {
                $sql = "UPDATE electeur SET nom = :nom, prenom = :prenom";
                $params = [':nom' => $nom, ':prenom' => $prenom, ':email' => $email];
                
                if (!empty($newPassword) && !empty($currentPassword)) {
                    $sql .= ", mot_de_passe = :password";
                    $params[':password'] = password_hash($newPassword, PASSWORD_DEFAULT);
                }
                
                $sql .= " WHERE email = :email";
                $stmt = $connexion->prepare($sql);
                $stmt->execute($params);
                
                $success = "Informations mises a jour avec succes.";
                
                // Rafraichir les donnees
                $stmt = $connexion->prepare("SELECT * FROM electeur WHERE email = :email");
                $stmt->execute([':email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $errors[] = "Erreur lors de la mise a jour.";
            }
        }
        
    } elseif ($userType === 'candidat') {
        // Candidat peut modifier : surnom (optionnel), photo et mot de passe
        $surnom = trim($_POST['surnom'] ?? '');
        $currentPassword = trim($_POST['current_password'] ?? '');
        $newPassword = trim($_POST['new_password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');
        $photo = $_FILES['photo'] ?? null;
        
        // Si on veut changer le mot de passe
        if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
            if (empty($currentPassword)) {
                $errors[] = "Veuillez entrer votre mot de passe actuel.";
            } elseif (!password_verify($currentPassword, $user['mot_de_passe'])) {
                $errors[] = "Le mot de passe actuel est incorrect.";
            } elseif (empty($newPassword)) {
                $errors[] = "Veuillez entrer un nouveau mot de passe.";
            } elseif (!validerMotDePasse($newPassword)) {
                $errors[] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.";
            } elseif ($newPassword !== $confirmPassword) {
                $errors[] = "Les nouveaux mots de passe ne correspondent pas.";
            }
        }
        
        if (empty($errors)) {
            try {
                $sql = "UPDATE candidat SET surnom = :surnom";
                $params = [':surnom' => $surnom ?: null, ':email' => $email];
                
                // Upload photo si fournie
                if ($photo && $photo['error'] === 0) {
                    $photoPath = uploadPhoto($photo);
                    if ($photoPath) {
                        $sql .= ", photo_profil = :photo";
                        $params[':photo'] = $photoPath;
                    }
                }
                
                // Nouveau mot de passe validé
                if (!empty($newPassword) && !empty($currentPassword)) {
                    $sql .= ", mot_de_passe = :password";
                    $params[':password'] = password_hash($newPassword, PASSWORD_DEFAULT);
                }
                
                $sql .= " WHERE email = :email";
                $stmt = $connexion->prepare($sql);
                $stmt->execute($params);
                
                // Recharger les données utilisateur après modification
                $stmt = $connexion->prepare("SELECT * FROM candidat WHERE email = :email");
                $stmt->execute([':email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $success = "Informations mises à jour avec succès.";
            } catch (PDOException $e) {
                $errors[] = "Erreur lors de la mise à jour.";
            }
        }
    }
}

require_once '../includes/header.php';
?>

<?php require_once '../includes/header.php'; ?>





<main class="flex-1">
    <div class="min-h-[70vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="w-full max-w-2xl bg-white rounded-xl shadow-lg p-6 sm:p-8">
            <?php if ($userType === 'candidat' && !empty($user['photo_profil'])): ?>
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-3xl font-bold text-noir">Mon Profil</h1>
                    <img src="<?php echo htmlspecialchars($user['photo_profil']); ?>" alt="Photo de profil" 
                        class="h-24 w-24 object-cover rounded-full border-2 border-gray-300">
                </div>
            <?php else: ?>
                <h1 class="text-3xl font-bold text-center text-noir mb-6">Mon Profil</h1>
            <?php endif; ?>

            <!-- Messages d'erreur -->
            <?php if (!empty($errors)): ?>
                <div class="mb-4 rounded-md bg-error/10 border border-error p-4">
                    <ul class="list-disc list-inside text-sm text-error">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Message de succès -->
            <?php if ($success): ?>
                <div class="mb-4 rounded-md bg-success/10 border border-success p-4 text-sm text-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <?php if ($user): ?>

                <?php if ($userType === 'candidat'): ?>
                    <!-- PROFIL CANDIDAT - Modifiable : surnom, photo, mot de passe -->
                    <form method="post" enctype="multipart/form-data" class="space-y-6">
                        <!-- Informations non modifiables -->
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <h2 class="text-lg font-semibold text-gray-900 mb-3">Informations personnelles</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Prénom</p>
                                    <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($user['prenom'] ?? ''); ?></p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nom</p>
                                    <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($user['nom'] ?? ''); ?></p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Email</p>
                                    <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Âge</p>
                                    <p class="mt-1 text-gray-900">
                                        <?php 
                                        if (!empty($user['date_naissance'])) {
                                            echo calculerAge($user['date_naissance']) . ' ans';
                                        } else {
                                            echo 'Non renseigné';
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nationalité</p>
                                    <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($user['nationalite'] ?? 'Non renseigné'); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Informations modifiables -->
                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-gray-900">Informations modifiables</h2>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Surnom de combat</label>
                                <input type="text" name="surnom" value="<?php echo htmlspecialchars($user['surnom'] ?? ''); ?>"
                                    class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu"
                                    placeholder="Optionnel">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Photo de profil</label>
                                <?php if (!empty($user['photo_profil'])): ?>
                                    <div class="mb-3">
                                        <p class="text-xs text-gray-500 mb-2">Photo actuelle :</p>
                                        <img src="<?php echo htmlspecialchars($user['photo_profil']); ?>" alt="Photo actuelle" 
                                            class="h-32 w-32 object-cover rounded-full border-2 border-gray-300">
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="photo" accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-bleu file:text-white hover:file:bg-bleu/90">
                                <p class="mt-1 text-xs text-gray-500">Laissez vide pour ne pas changer</p>
                            </div>

                            <div class="border-t pt-4">
                                <h3 class="text-md font-semibold text-gray-900 mb-3">Changer le mot de passe</h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                                        <input type="password" name="current_password" placeholder="Entrez votre mot de passe actuel"
                                            class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                                        <input type="password" name="new_password" placeholder="Entrez le nouveau mot de passe"
                                            class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                                        <input type="password" name="confirm_password" placeholder="Confirmez le nouveau mot de passe"
                                            class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <a href="supprimer_compte.php" 
                               class="px-4 py-2 text-sm text-rouge border border-rouge rounded-md hover:bg-rouge hover:text-white transition-colors">
                                Supprimer mon compte
                            </a>
                            <button type="submit"
                                class="px-6 py-3 rounded-md bg-bleu text-white font-medium shadow hover:bg-bleu/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bleu">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>

                <?php elseif ($userType === 'electeur'): ?>
                    <!-- PROFIL ELECTEUR - Afficher toutes les infos, modification limitee a nom et prenom -->
                    <form method="post" class="space-y-6">
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3 mb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-3">Informations personnelles</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                                    <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom'] ?? ''); ?>" required
                                        class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prenom *</label>
                                    <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['prenom'] ?? ''); ?>" required
                                        class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu">
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <h3 class="text-sm font-semibold text-gray-900 mb-3">Informations non modifiables</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Email</p>
                                        <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Age</p>
                                        <p class="mt-1 text-gray-900">
                                            <?php 
                                            if (!empty($user['age'])) {
                                                echo htmlspecialchars($user['age']) . ' ans';
                                            } else {
                                                echo 'Non renseigne';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Sexe</p>
                                        <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($user['sexe'] ?? 'Non renseigne'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Nationalite</p>
                                        <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($user['nationalite'] ?? 'Non renseignee'); ?></p>
                                    </div>
                                    <?php if (!empty($user['code_fourni'])): ?>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Type de professionnel</p>
                                        <p class="mt-1 text-gray-900">
                                            <?php 
                                            $sqlCollege = "SELECT type FROM college WHERE ID_college = :id";
                                            $stmtCollege = $connexion->prepare($sqlCollege);
                                            $stmtCollege->execute([':id' => $user['id_college']]);
                                            $college = $stmtCollege->fetch(PDO::FETCH_ASSOC);
                                            if ($college && $college['type'] === 'journaliste') {
                                                echo 'Journaliste';
                                            } elseif ($college && $college['type'] === 'coach') {
                                                echo 'Coach';
                                            } else {
                                                echo 'Electeur public';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t pt-4">
                            <h3 class="text-md font-semibold text-gray-900 mb-3">Changer le mot de passe</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                                    <input type="password" name="current_password" placeholder="Entrez votre mot de passe actuel"
                                        class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                                    <input type="password" name="new_password" placeholder="Entrez le nouveau mot de passe"
                                        class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                                    <input type="password" name="confirm_password" placeholder="Confirmez le nouveau mot de passe"
                                        class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <a href="supprimer_compte.php" 
                               class="px-4 py-2 text-sm text-rouge border border-rouge rounded-md hover:bg-rouge hover:text-white transition-colors">
                                Supprimer mon compte
                            </a>
                            <button type="submit"
                                class="px-6 py-3 rounded-md bg-bleu text-white font-medium shadow hover:bg-bleu/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bleu">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>

                <?php elseif ($userType === 'administrateur'): ?>
                    <!-- PROFIL ADMIN - Tout modifiable sauf email -->
                    <form method="post" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                                <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom'] ?? ''); ?>" required
                                    class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['prenom'] ?? ''); ?>" required
                                    class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled
                                    class="block w-full rounded-md border border-gray-300 bg-gray-100 shadow-sm px-3 py-2 text-gray-500 cursor-not-allowed">
                                <p class="mt-1 text-xs text-gray-500">L'email ne peut pas être modifié</p>
                            </div>
                        </div>
                        
                        <div class="border-t pt-4">
                            <h3 class="text-md font-semibold text-gray-900 mb-3">Changer le mot de passe</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                                    <input type="password" name="current_password" placeholder="Entrez votre mot de passe actuel"
                                        class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                                    <input type="password" name="new_password" placeholder="Entrez le nouveau mot de passe"
                                        class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                                    <input type="password" name="confirm_password" placeholder="Confirmez le nouveau mot de passe"
                                        class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <a href="supprimer_compte.php" 
                               class="px-4 py-2 text-sm text-rouge border border-rouge rounded-md hover:bg-rouge hover:text-white transition-colors">
                                Supprimer mon compte
                            </a>
                            <button type="submit"
                                class="px-6 py-3 rounded-md bg-bleu text-white font-medium shadow hover:bg-bleu/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bleu">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                <?php endif; ?>

            <?php endif; ?>

        </div>
    </div>
</main>



<?php require_once '../includes/footer.php'; ?>
