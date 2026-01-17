<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/config.php';

$conn = dbconnect();
$id_post = $_GET['id'] ?? null;
$error = '';
$success = '';

if (!$id_post) {
    header('Location: posts.php');
    exit;
}

// Traitement ajout commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['isConnected']) && $_SESSION['user_type'] === 'electeur') {
    $contenu = trim($_POST['contenu'] ?? '');
    
    if (empty($contenu)) {
        $error = 'Le commentaire ne peut pas etre vide.';
    } else {
        $sql = "SELECT ID_electeur FROM electeur WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':email' => $_SESSION['email']]);
        $electeur = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($electeur) {
            $sql = "INSERT INTO commentaire (id_post, id_electeur, contenu) VALUES (:id_post, :id_electeur, :contenu)";
            $stmt = $conn->prepare($sql);
            $result = $stmt->execute([
                ':id_post' => $id_post,
                ':id_electeur' => $electeur['ID_electeur'],
                ':contenu' => $contenu
            ]);
            
            if ($result) {
                $success = 'Commentaire ajoute!';
            } else {
                $error = 'Erreur lors de l\'ajout du commentaire.';
            }
        }
    }
}

// Recuperer le post
$sql = "SELECT p.*, c.prenom, c.nom, c.photo_profil 
        FROM post p 
        INNER JOIN candidat c ON p.id_candidat = c.ID_candidat 
        WHERE p.ID_post = :id_post";
$stmt = $conn->prepare($sql);
$stmt->execute([':id_post' => $id_post]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header('Location: posts.php');
    exit;
}

// Compter likes et dislikes
$sql = "SELECT COUNT(*) as count FROM reaction WHERE id_post = :id_post AND type_reaction = 'like'";
$stmt = $conn->prepare($sql);
$stmt->execute([':id_post' => $id_post]);
$nb_likes = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

$sql = "SELECT COUNT(*) as count FROM reaction WHERE id_post = :id_post AND type_reaction = 'dislike'";
$stmt = $conn->prepare($sql);
$stmt->execute([':id_post' => $id_post]);
$nb_dislikes = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Recuperer les commentaires
$sql = "SELECT com.*, e.prenom, e.nom 
        FROM commentaire com 
        INNER JOIN electeur e ON com.id_electeur = e.ID_electeur 
        WHERE com.id_post = :id_post 
        ORDER BY com.date_creation DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([':id_post' => $id_post]);
$commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<main class="flex-1 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <a href="posts.php" class="text-bleu hover:underline mb-4 inline-block">&larr; Retour aux posts</a>
        
        <!-- Post -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="p-4 border-b">
                <div class="flex items-center">
                    <?php if ($post['photo_profil']): ?>
                        <img src="../<?php echo htmlspecialchars($post['photo_profil']); ?>" 
                             alt="Photo" 
                             class="w-12 h-12 rounded-full object-cover mr-3">
                    <?php endif; ?>
                    <div>
                        <h3 class="font-semibold text-noir">
                            <?php echo htmlspecialchars($post['prenom'] . ' ' . $post['nom']); ?>
                        </h3>
                        <p class="text-sm text-gray-500">
                            <?php echo date('d/m/Y H:i', strtotime($post['date_creation'])); ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-black">
                <?php if ($post['type_media'] === 'image'): ?>
                    <img src="../<?php echo htmlspecialchars($post['chemin_media']); ?>" 
                         alt="Post" 
                         class="w-full max-h-[500px] object-contain mx-auto">
                <?php else: ?>
                    <video controls class="w-full max-h-[500px] mx-auto">
                        <source src="../<?php echo htmlspecialchars($post['chemin_media']); ?>">
                    </video>
                <?php endif; ?>
            </div>
            
            <div class="p-4">
                <div class="flex gap-4 mb-3">
                    <div class="flex items-center gap-2 text-gray-600">
                        <span class="text-xl">👍</span>
                        <span><?php echo $nb_likes; ?></span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-600">
                        <span class="text-xl">👎</span>
                        <span><?php echo $nb_dislikes; ?></span>
                    </div>
                </div>
                
                <?php if ($post['description']): ?>
                    <p class="text-gray-700">
                        <span class="font-semibold"><?php echo htmlspecialchars($post['prenom']); ?></span>
                        <?php echo nl2br(htmlspecialchars($post['description'])); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Formulaire commentaire -->
        <?php if (isset($_SESSION['isConnected']) && $_SESSION['user_type'] === 'electeur'): ?>
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <h3 class="font-semibold mb-3">Ajouter un commentaire</h3>
                
                <?php if ($error): ?>
                    <div class="bg-error/10 border border-error text-error px-4 py-3 rounded mb-4">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="bg-success/10 border border-success text-success px-4 py-3 rounded mb-4">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <textarea 
                        name="contenu" 
                        rows="3" 
                        placeholder="Votre commentaire..."
                        required
                        class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu mb-3"
                    ></textarea>
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-bleu text-white rounded-md hover:bg-bleu/90 transition-colors"
                    >
                        Commenter
                    </button>
                </form>
            </div>
        <?php endif; ?>
        
        <!-- Liste commentaires -->
        <div class="bg-white rounded-lg shadow-md p-4">
            <h3 class="font-semibold mb-4">Commentaires (<?php echo count($commentaires); ?>)</h3>
            
            <?php if (empty($commentaires)): ?>
                <p class="text-gray-500">Aucun commentaire pour le moment.</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($commentaires as $comment): ?>
                        <div class="border-b pb-4 last:border-b-0">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="font-semibold text-noir">
                                        <?php echo htmlspecialchars($comment['prenom'] . ' ' . $comment['nom']); ?>
                                    </span>
                                    <span class="text-sm text-gray-500 ml-2">
                                        <?php echo date('d/m/Y H:i', strtotime($comment['date_creation'])); ?>
                                    </span>
                                </div>
                                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'administrateur'): ?>
                                    <a href="supprimer_commentaire.php?id=<?php echo $comment['ID_commentaire']; ?>&post=<?php echo $id_post; ?>" 
                                       onclick="return confirm('Supprimer ce commentaire?')"
                                       class="text-rouge text-sm hover:underline">
                                        Supprimer
                                    </a>
                                <?php endif; ?>
                            </div>
                            <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($comment['contenu'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
