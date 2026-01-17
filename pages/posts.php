<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/config.php';

$conn = dbconnect();

// Recuperer tous les posts avec infos candidat
$sql = "SELECT p.*, c.prenom, c.nom, c.photo_profil 
        FROM post p 
        INNER JOIN candidat c ON p.id_candidat = c.ID_candidat 
        ORDER BY p.date_creation DESC";
$stmt = $conn->query($sql);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pour chaque post, recuperer le nombre de likes/dislikes et commentaires
foreach ($posts as &$post) {
    // Compter likes
    $sql = "SELECT COUNT(*) as count FROM reaction WHERE id_post = :id_post AND type_reaction = 'like'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_post' => $post['ID_post']]);
    $post['nb_likes'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Compter dislikes
    $sql = "SELECT COUNT(*) as count FROM reaction WHERE id_post = :id_post AND type_reaction = 'dislike'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_post' => $post['ID_post']]);
    $post['nb_dislikes'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Compter commentaires
    $sql = "SELECT COUNT(*) as count FROM commentaire WHERE id_post = :id_post";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_post' => $post['ID_post']]);
    $post['nb_commentaires'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Si electeur connecte, verifier sa reaction
    if (isset($_SESSION['isConnected']) && $_SESSION['user_type'] === 'electeur') {
        $sql_electeur = "SELECT ID_electeur FROM electeur WHERE email = :email";
        $stmt_electeur = $conn->prepare($sql_electeur);
        $stmt_electeur->execute([':email' => $_SESSION['email']]);
        $electeur = $stmt_electeur->fetch(PDO::FETCH_ASSOC);
        
        if ($electeur) {
            $sql = "SELECT type_reaction FROM reaction WHERE id_post = :id_post AND id_electeur = :id_electeur";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':id_post' => $post['ID_post'],
                ':id_electeur' => $electeur['ID_electeur']
            ]);
            $reaction = $stmt->fetch(PDO::FETCH_ASSOC);
            $post['ma_reaction'] = $reaction ? $reaction['type_reaction'] : null;
        }
    }
}

require_once '../includes/header.php';
?>

<main class="flex-1 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-3xl font-bold text-noir mb-6">Posts des Candidats</h1>
        
        <?php if (empty($posts)): ?>
            <p class="text-gray-500">Aucun post pour le moment.</p>
        <?php else: ?>
            <div class="space-y-6">
                <?php foreach ($posts as $post): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <!-- En-tete du post -->
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
                        
                        <!-- Media -->
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
                        
                        <!-- Actions et description -->
                        <div class="p-4">
                            <!-- Boutons like/dislike -->
                            <div class="flex gap-4 mb-3">
                                <?php if (isset($_SESSION['isConnected']) && $_SESSION['user_type'] === 'electeur'): ?>
                                    <a href="reagir_post.php?id=<?php echo $post['ID_post']; ?>&type=like" 
                                       class="flex items-center gap-2 <?php echo isset($post['ma_reaction']) && $post['ma_reaction'] === 'like' ? 'text-bleu' : 'text-gray-600'; ?> hover:text-bleu">
                                        <span class="text-xl">👍</span>
                                        <span><?php echo $post['nb_likes']; ?></span>
                                    </a>
                                    <a href="reagir_post.php?id=<?php echo $post['ID_post']; ?>&type=dislike" 
                                       class="flex items-center gap-2 <?php echo isset($post['ma_reaction']) && $post['ma_reaction'] === 'dislike' ? 'text-rouge' : 'text-gray-600'; ?> hover:text-rouge">
                                        <span class="text-xl">👎</span>
                                        <span><?php echo $post['nb_dislikes']; ?></span>
                                    </a>
                                <?php else: ?>
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <span class="text-xl">👍</span>
                                        <span><?php echo $post['nb_likes']; ?></span>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <span class="text-xl">👎</span>
                                        <span><?php echo $post['nb_dislikes']; ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <a href="voir_post.php?id=<?php echo $post['ID_post']; ?>" 
                                   class="flex items-center gap-2 text-gray-600 hover:text-bleu">
                                    <span class="text-xl">💬</span>
                                    <span><?php echo $post['nb_commentaires']; ?></span>
                                </a>
                            </div>
                            
                            <!-- Description -->
                            <?php if ($post['description']): ?>
                                <p class="text-gray-700">
                                    <span class="font-semibold"><?php echo htmlspecialchars($post['prenom']); ?></span>
                                    <?php echo nl2br(htmlspecialchars($post['description'])); ?>
                                </p>
                            <?php endif; ?>
                            
                            <!-- Lien voir commentaires -->
                            <?php if ($post['nb_commentaires'] > 0): ?>
                                <a href="voir_post.php?id=<?php echo $post['ID_post']; ?>" 
                                   class="text-sm text-gray-500 hover:text-gray-700 mt-2 inline-block">
                                    Voir les <?php echo $post['nb_commentaires']; ?> commentaire<?php echo $post['nb_commentaires'] > 1 ? 's' : ''; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
