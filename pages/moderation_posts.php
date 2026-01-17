<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/config.php';

// Verifier que c'est un admin
if (!isset($_SESSION['isConnected']) || $_SESSION['user_type'] !== 'administrateur') {
    header('Location: login.php');
    exit;
}

$conn = dbconnect();

// Recuperer tous les posts
$sql = "SELECT p.*, c.prenom, c.nom 
        FROM post p 
        INNER JOIN candidat c ON p.id_candidat = c.ID_candidat 
        ORDER BY p.date_creation DESC";
$stmt = $conn->query($sql);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Recuperer tous les commentaires recents
$sql = "SELECT com.*, e.prenom as prenom_electeur, e.nom as nom_electeur, p.ID_post
        FROM commentaire com 
        INNER JOIN electeur e ON com.id_electeur = e.ID_electeur 
        INNER JOIN post p ON com.id_post = p.ID_post
        ORDER BY com.date_creation DESC 
        LIMIT 50";
$stmt = $conn->query($sql);
$commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<main class="flex-1 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <h1 class="text-3xl font-bold text-noir mb-6">Moderation</h1>
        
        <!-- Section Posts -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">Posts (<?php echo count($posts); ?>)</h2>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Candidat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php echo htmlspecialchars($post['prenom'] . ' ' . $post['nom']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php echo $post['type_media'] === 'image' ? 'Image' : 'Video'; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo date('d/m/Y', strtotime($post['date_creation'])); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <?php echo substr(htmlspecialchars($post['description'] ?? ''), 0, 50); ?>
                                    <?php if (strlen($post['description'] ?? '') > 50) echo '...'; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="voir_post.php?id=<?php echo $post['ID_post']; ?>" 
                                       class="text-bleu hover:underline mr-3">
                                        Voir
                                    </a>
                                    <a href="supprimer_post.php?id=<?php echo $post['ID_post']; ?>" 
                                       onclick="return confirm('Supprimer ce post?')"
                                       class="text-rouge hover:underline">
                                        Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Section Commentaires -->
        <div>
            <h2 class="text-2xl font-semibold mb-4">Commentaires recents (<?php echo count($commentaires); ?>)</h2>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Electeur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contenu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($commentaires as $comment): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php echo htmlspecialchars($comment['prenom_electeur'] . ' ' . $comment['nom_electeur']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo date('d/m/Y', strtotime($comment['date_creation'])); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <?php echo substr(htmlspecialchars($comment['contenu']), 0, 80); ?>
                                    <?php if (strlen($comment['contenu']) > 80) echo '...'; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="voir_post.php?id=<?php echo $comment['ID_post']; ?>" 
                                       class="text-bleu hover:underline mr-3">
                                        Voir post
                                    </a>
                                    <a href="supprimer_commentaire.php?id=<?php echo $comment['ID_commentaire']; ?>" 
                                       onclick="return confirm('Supprimer ce commentaire?')"
                                       class="text-rouge hover:underline">
                                        Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
