<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

$action = $_GET['action'] ?? 'liste';

$isAdmin = isset($_SESSION['isConnected']) && $_SESSION['isConnected'] && $_SESSION['user_type'] === 'administrateur';

// Si action finaliser, rediriger vers candidat.php
if ($action === 'finaliser') {
    header('Location: candidat.php' . (isset($_GET['etape']) ? '?etape=' . $_GET['etape'] : ''));
    exit();
}

// Si admin, rediriger vers admin/index.php
if ($isAdmin) {
    header('Location: ../admin/index.php' . (isset($_GET['edit']) ? '?edit=' . $_GET['edit'] : ''));
    exit();
}

require_once __DIR__ . '/../includes/header.php';

$candidats = getTousCandidats();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidats - MMA Fighter Election</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Candidats</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <?php foreach ($candidats as $c): ?>
            <?php if ($c['compte_verifie'] && $c['compte_actif']): ?>
            <div class="border-b py-4 last:border-b-0">
                <p class="font-medium"><?php echo htmlspecialchars($c['prenom'] . ' ' . $c['nom']); ?></p>
                <?php if ($c['surnom']): ?>
                <p class="text-gray-600"><?php echo htmlspecialchars($c['surnom']); ?></p>
                <?php endif; ?>
                <p class="text-sm text-gray-500"><?php echo htmlspecialchars($c['nationalite']); ?></p>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
