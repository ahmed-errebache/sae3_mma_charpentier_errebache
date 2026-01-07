<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Verifier que c'est un candidat connecte
if (!isset($_SESSION['isConnected']) || $_SESSION['user_type'] !== 'candidat') {
    header('Location: login.php');
    exit;
}

$conn = dbconnect();
$email = $_SESSION['email'];
$error = '';
$success = '';

// Recuperer l'ID du candidat
$sql = "SELECT ID_candidat FROM candidat WHERE email = :email";
$stmt = $conn->prepare($sql);
$stmt->execute([':email' => $email]);
$candidat = $stmt->fetch(PDO::FETCH_ASSOC);
$id_candidat = $candidat['ID_candidat'];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description'] ?? '');
    $media = $_FILES['media'] ?? null;
    
    if (!$media || $media['error'] !== 0) {
        $error = 'Veuillez selectionner un fichier.';
    } else {
        $allowed_images = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $allowed_videos = ['video/mp4', 'video/mpeg', 'video/quicktime'];
        $mime_type = mime_content_type($media['tmp_name']);
        
        if (in_array($mime_type, $allowed_images)) {
            $type_media = 'image';
            $upload_dir = '../uploads/posts/images/';
        } elseif (in_array($mime_type, $allowed_videos)) {
            $type_media = 'video';
            $upload_dir = '../uploads/posts/videos/';
        } else {
            $error = 'Type de fichier non autorise.';
        }
        
        if (empty($error)) {
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $extension = pathinfo($media['name'], PATHINFO_EXTENSION);
            $filename = uniqid('post_') . '.' . $extension;
            $filepath = $upload_dir . $filename;
            
            if (move_uploaded_file($media['tmp_name'], $filepath)) {
                $chemin_bdd = 'uploads/posts/' . ($type_media === 'image' ? 'images/' : 'videos/') . $filename;
                
                $sql = "INSERT INTO post (id_candidat, type_media, chemin_media, description) 
                        VALUES (:id_candidat, :type_media, :chemin_media, :description)";
                $stmt = $conn->prepare($sql);
                $result = $stmt->execute([
                    ':id_candidat' => $id_candidat,
                    ':type_media' => $type_media,
                    ':chemin_media' => $chemin_bdd,
                    ':description' => $description
                ]);
                
                if ($result) {
                    $success = 'Post publie avec succes!';
                } else {
                    $error = 'Erreur lors de la publication.';
                }
            } else {
                $error = 'Erreur lors du telechargement du fichier.';
            }
        }
    }
}

// Recuperer les posts du candidat
$sql = "SELECT * FROM post WHERE id_candidat = :id_candidat ORDER BY date_creation DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([':id_candidat' => $id_candidat]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<main class="flex-1 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-3xl font-bold text-noir mb-6">Mes Posts</h1>
        
        <!-- Formulaire creation post -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Creer un nouveau post</h2>
            
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
            
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Image ou Video
                    </label>
                    <input 
                        type="file" 
                        name="media" 
                        accept="image/*,video/*"
                        required
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-bleu file:text-white hover:file:bg-bleu/90"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Description (optionnel)
                    </label>
                    <textarea 
                        name="description" 
                        rows="3"
                        placeholder="Ajoutez une description..."
                        class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-bleu focus:border-bleu"
                    ></textarea>
                </div>
                
                <button 
                    type="submit"
                    class="px-6 py-2 bg-bleu text-white rounded-md hover:bg-bleu/90 transition-colors font-medium"
                >
                    Publier
                </button>
            </form>
        </div>
        
        <!-- Liste des posts -->
        <div class="space-y-4">
            <h2 class="text-2xl font-semibold">Mes publications</h2>
            
            <?php if (empty($posts)): ?>
                <p class="text-gray-500">Vous n'avez pas encore publie de post.</p>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-sm text-gray-500">
                                <?php echo date('d/m/Y H:i', strtotime($post['date_creation'])); ?>
                            </span>
                            <a href="supprimer_post.php?id=<?php echo $post['ID_post']; ?>" 
                               onclick="return confirm('Supprimer ce post?')"
                               class="text-rouge text-sm hover:underline">
                                Supprimer
                            </a>
                        </div>
                        
                        <?php if ($post['type_media'] === 'image'): ?>
                            <img src="../<?php echo htmlspecialchars($post['chemin_media']); ?>" 
                                 alt="Post" 
                                 class="w-full max-h-96 object-cover rounded mb-3">
                        <?php else: ?>
                            <video controls class="w-full max-h-96 rounded mb-3">
                                <source src="../<?php echo htmlspecialchars($post['chemin_media']); ?>">
                            </video>
                        <?php endif; ?>
                        
                        <?php if ($post['description']): ?>
                            <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($post['description'])); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
