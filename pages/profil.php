<?php

session_start();

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

// Récupération des infos
$Email = $_SESSION['email'] ?? null;
$UserType = $_SESSION['user_type'] ?? null; // 'electeur', 'candidat' ou 'administrateur'

if (!$Email || !$UserType) {
    // Si les infos de session sont incomplètes, redirige vers la connexion
    header('Location: login.php');
    exit;
}



$errors  = [];
$success = '';

// ------------------------------------------------------
//  TRAITEMENT DU FORMULAIRE (électeur + administrateur)
// ------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Seuls un électeur et un administrateur peuvent modifier leurs infos
    if ($UserType === 'electeur') {

        $nom         = trim($_POST['nom'] ?? '');
        $prenom      = trim($_POST['prenom'] ?? '');
        $age         = trim($_POST['age'] ?? '');
        $nationalite = trim($_POST['nationalite'] ?? '');
        $sexe        = trim($_POST['sexe'] ?? '');
        $email       = trim($_POST['email'] ?? '');
        $password    = trim($_POST['password'] ?? '');

        // Vérifications des champs
        if ($nom === '' || $prenom === '' || $age === '' || $nationalite === '' || $sexe === '' || $email === '' || $password === '') {
            $errors[] = "Tous les champs sont obligatoires.";
        }

        if (!ctype_digit($age) || (int)$age <= 0) {
            $errors[] = "L'âge doit être un nombre positif.";
        }

        //  Sexe limité à Homme / Femme
        if (!in_array($sexe, ['Homme', 'Femme'], true)) {
            $errors[] = "Le sexe doit être « Homme » ou « Femme ».";
        }

        if (empty($errors)) {
            try {
                $sql = "UPDATE electeur
                        SET nom = :nom,
                            prenom = :prenom,
                            age = :age,
                            nationalite = :nationalite,
                            sexe = :sexe,
                            email = :email,
                            mot_de_passe = :password
                        WHERE email = :old_email";

                $stmt = $connexion->prepare($sql);
                $stmt->execute([
                    ':nom'        => $nom,
                    ':prenom'     => $prenom,
                    ':age'        => (int)$age,
                    ':nationalite'=> $nationalite,
                    ':sexe'       => $sexe,
                    ':email'      => $email,
                    ':password'   => $password,
                    ':old_email'  => $Email
                ]);

                // Si l'email a changé, on met à jour la variable de session
                $_SESSION['email'] = $email;
                $Email             = $email;

                $success = "Vos informations ont été mises à jour avec succès.";
            } catch (PDOException $e) {
                $errors[] = "Une erreur est survenue lors de la mise à jour : " . $e->getMessage();
            }
        }

    } elseif ($UserType === 'administrateur') {

        $nom      = trim($_POST['nom'] ?? '');
        $prenom   = trim($_POST['prenom'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($nom === '' || $prenom === '' || $email === '' || $password === '') {
            $errors[] = "Tous les champs sont obligatoires.";
        }

        if (empty($errors)) {
            try {
                $sql = "UPDATE administrateur
                        SET nom = :nom,
                            prenom = :prenom,
                            email = :email,
                            mot_de_passe = :password
                        WHERE email = :old_email";

                $stmt = $connexion->prepare($sql);
                $stmt->execute([
                    ':nom'       => $nom,
                    ':prenom'    => $prenom,
                    ':email'     => $email,
                    ':password'  => $password,
                    ':old_email' => $Email
                ]);

                $_SESSION['email'] = $email;
                $Email      = $email;

                $success = "Vos informations ont été mises à jour avec succès.";
            } catch (PDOException $e) {
                $errors[] = "Une erreur est survenue lors de la mise à jour : " . $e->getMessage();
            }
        }

    } else {
        // Candidat : pas de modification autorisée
        $errors[] = "Vous n'êtes pas autorisé à modifier votre profil.";
    }
}

// --------------------------------------
//  RÉCUPÉRATION DES DONNÉES UTILISATEUR
// --------------------------------------
try {
    // On récupère les infos via l'email de la variable de session
    $sql = "SELECT * FROM $UserType WHERE email = :email";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([':email' => $Email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $errors[] = "Impossible de récupérer vos informations.";
    }
} catch (PDOException $e) {
    $errors[] = "Erreur lors de la récupération des informations : " . $e->getMessage();
}

?>

<?php require_once '../includes/header.php'; ?>





<main class="flex-1">
    <div class="min-h-[70vh] flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-2xl bg-white rounded-xl shadow-lg p-6 sm:p-8">
            <h1 class="text-2xl font-bold text-center text-noir mb-6">
                Mon profil
            </h1>

            <!-- Messages d'erreur -->
            <?php if (!empty($errors)): ?>
                <div class="mb-4 rounded-md bg-error/10 p-4">
                    <ul class="list-disc list-inside text-sm text-error">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Message de succès -->
            <?php if ($success): ?>
                <div class="mb-4 rounded-md bg-success/10 p-4 text-sm text-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <?php if ($user): ?>

                <?php if ($UserType === 'candidat'): ?>
                    <!-- PROFIL CANDIDAT -->
                    <div class="space-y-4">
                        <p class="text-sm text-gray-500 mb-2">
                            En tant que <span class="font-semibold">candidat</span>, vous ne pouvez pas modifier vos informations depuis cette page.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h2 class="text-sm font-medium text-gray-500">Prénom</h2>
                                <p class="mt-1 text-noir"><?php echo htmlspecialchars($user['prenom'] ?? ''); ?></p>
                            </div>
                            <div>
                                <h2 class="text-sm font-medium text-gray-500">Surnom</h2>
                                <p class="mt-1 text-noir"><?php echo htmlspecialchars($user['surnom'] ?? ''); ?></p>
                            </div>
                            <div>
                                <h2 class="text-sm font-medium text-gray-500">Nom</h2>
                                <p class="mt-1 text-noir"><?php echo htmlspecialchars($user['nom'] ?? ''); ?></p>
                            </div>
                            <div>
                                <h2 class="text-sm font-medium text-gray-500">Nationalité</h2>
                                <p class="mt-1 text-noir"><?php echo htmlspecialchars($user['nationalite'] ?? ''); ?></p>
                            </div>
                            <div>
                                <h2 class="text-sm font-medium text-gray-500">Palmarès de l'année</h2>
                                <p class="mt-1 text-noir"><?php echo htmlspecialchars($user['palmares_annee'] ?? ''); ?></p>
                            </div>
                            <div>
                                <h2 class="text-sm font-medium text-gray-500">Email</h2>
                                <p class="mt-1 text-noir"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
                            </div>
                            <div>
                                <h2 class="text-sm font-medium text-gray-500">Mot de passe</h2>
                                <p class="mt-1 text-noir"><?php echo htmlspecialchars($user['mot_de_passe'] ?? ''); ?></p>
                            </div>
                        </div>

                        <?php if (!empty($user['photo_profil'])): ?>
                            <div class="mt-6">
                                <h2 class="text-sm font-medium text-gray-500 mb-2">Photo de profil</h2>
                                <img src="<?php echo htmlspecialchars($user['photo_profil']); ?>" alt="Photo de profil" class="h-40 w-40 object-cover rounded-full border">
                            </div>
                        <?php endif; ?>
                    </div>

                <?php elseif ($UserType === 'electeur'): ?>
                    <!-- PROFIL ELECTEUR -->
                    <form method="post" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nom</label>
                                <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom'] ?? ''); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-bleu focus:border-bleu">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Prénom</label>
                                <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['prenom'] ?? ''); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-bleu focus:border-bleu">
                            </div>
                            <div>
                                <!-- Age limité à des entiers -->
                                <label class="block text-sm font-medium text-gray-700">Âge</label>
                                <input type="number" name="age" value="<?php echo htmlspecialchars($user['age'] ?? ''); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-bleu focus:border-bleu" min="1">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nationalité</label>
                                <input type="text" name="nationalite" value="<?php echo htmlspecialchars($user['nationalite'] ?? ''); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-bleu focus:border-bleu">
                            </div>
                            <div>
                                <!-- Sexe limité à Homme / Femme -->
                                <label class="block text-sm font-medium text-gray-700">Sexe</label>
                                <select name="sexe"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-bleu focus:border-bleu">
                                    <option value="">-- Sélectionnez --</option>
                                    <option value="Homme" <?php echo (isset($user['sexe']) && $user['sexe'] === 'Homme') ? 'selected' : ''; ?>>
                                        Homme
                                    </option>
                                    <option value="Femme" <?php echo (isset($user['sexe']) && $user['sexe'] === 'Femme') ? 'selected' : ''; ?>>
                                        Femme
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Adresse mail</label>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-bleu focus:border-bleu">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Mot de passe</label>
                                <input type="text" name="password" value="<?php echo htmlspecialchars($user['mot_de_passe'] ?? ''); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-bleu focus:border-bleu">
                            </div>
                        </div>

                        <!-- bouton enregistrer -->
                        <div class="pt-4 flex justify-end">
                            <button type="submit"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-bleu px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-bleu/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bleu">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>

                <?php elseif ($UserType === 'administrateur'): ?>
                    <!-- PROFIL ADMIN -->
                    <form method="post" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nom</label>
                                <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom'] ?? ''); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-bleu focus:border-bleu">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Prénom</label>
                                <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['prenom'] ?? ''); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-bleu focus:border-bleu">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Adresse mail</label>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-bleu focus:border-bleu">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Mot de passe</label>
                                <input type="text" name="password" value="<?php echo htmlspecialchars($user['mot_de_passe'] ?? ''); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-bleu focus:border-bleu">
                            </div>
                        </div>

                        <!-- bouton enregistrer -->
                        <div class="pt-4 flex justify-end">
                            <button type="submit"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-bleu px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-bleu/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bleu">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                <?php endif; ?>

                <!-- bouton déconnexion -->
                <div class="mt-8 flex justify-center">
                    <a href="profil.php?action=logout"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm bg-rouge text-white hover:bg-rouge/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rouge">
                        Se déconnecter
                    </a>
                </div>


            <?php endif; ?>

        </div>
    </div>
</main>



<?php require_once '../includes/footer.php'; ?>
