<?php
session_start();

require_once '../includes/config.php'; 
$connexion = dbconnect();

// Traitement du formulaire
$message_sent = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));
    
    if (!empty($nom) && !empty($email) && !empty($message) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        require '../vendor/autoload.php';
        
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ahmed.errebache@gmail.com';
            $mail->Password = 'cnij ihjw zmbw qxyh';
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';
            
            $mail->setFrom('contact@mma-election.ahmed-errebache.space', 'MMA Fighter Election');
            $mail->addAddress('contact@mma-election.ahmed-errebache.space');
            $mail->addReplyTo($email, $nom);
            
            $mail->isHTML(true);
            $mail->Subject = 'Nouveau message de contact - MMA Fighter Election';
            $mail->Body = "
                <h2>Nouveau message de contact</h2>
                <p><strong>Nom:</strong> {$nom}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Message:</strong></p>
                <p>{$message}</p>
            ";
            $mail->AltBody = "Nom: {$nom}\nEmail: {$email}\nMessage: {$message}";
            
            $mail->send();
            $message_sent = true;
        } catch (Exception $e) {
            $error_message = "Erreur lors de l'envoi du message. Veuillez réessayer.";
        }
    } else {
        $error_message = "Veuillez remplir tous les champs correctement.";
    }
}

require_once '../includes/header.php'; 
?>

<!-- FORMULAIRE DE CONTACT -->
<div class="min-h-screen bg-gris-clair py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bebas text-noir mb-4 tracking-wide">
                CONTACT
            </h1>
            <p class="text-lg font-anek text-gray-600">
                Une question ? Contactez-nous
            </p>
        </div>

        <?php if ($message_sent): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                Votre message a été envoyé avec succès !
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-lg p-8 lg:p-12">
            <div class="grid lg:grid-cols-2 gap-12">
                
                <div>
                    <h2 class="text-2xl font-bebas text-noir mb-6 tracking-wide">
                        ENVOYEZ UN MESSAGE
                    </h2>
                    
                    <form method="POST" class="space-y-6">
                        
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom *
                            </label>
                            <input type="text" id="nom" name="nom" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rouge focus:border-rouge font-anek"
                                placeholder="Votre nom">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email *
                            </label>
                            <input type="email" id="email" name="email" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rouge focus:border-rouge font-anek"
                                placeholder="votre@email.com">
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Message *
                            </label>
                            <textarea id="message" name="message" rows="5" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rouge focus:border-rouge font-anek"
                                placeholder="Votre message..."></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-rouge text-white font-bebas text-lg py-3 px-6 rounded-lg tracking-wide hover:bg-rouge/90 transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-rouge/50 focus:ring-offset-2 shadow-lg">
                            ENVOYER
                        </button>
                    </form>
                </div>

                <div class="space-y-8">
                    <div>
                        <h3 class="text-xl font-bebas text-noir mb-4 tracking-wide">
                            INFORMATIONS
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-semibold text-noir font-anek">Email</h4>
                                <p class="text-gray-600 font-anek">contact@mmafighterelection.fr</p>
                            </div>
                            
                            <div>
                                <h4 class="font-semibold text-noir font-anek">Équipe</h4>
                                <p class="text-gray-600 font-anek">Lucas Charpentier & Ahmed Errebache</p>
                            </div>
                            
                            <div>
                                <h4 class="font-semibold text-noir font-anek">Projet</h4>
                                <p class="text-gray-600 font-anek">SAÉ S3 - IUT de Saint Die</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-bebas text-noir mb-4 tracking-wide">
                            QUESTIONS FRÉQUENTES
                        </h3>
                        
                        <div class="space-y-3">
                            <div>
                                <h4 class="font-medium text-gray-900 font-anek text-sm">Comment voter ?</h4>
                                <p class="text-gray-600 font-anek text-sm">Inscrivez-vous ou connectez-vous pour participer au vote.</p>
                            </div>
                            
                            <div>
                                <h4 class="font-medium text-gray-900 font-anek text-sm">Code professionnel ?</h4>
                                <p class="text-gray-600 font-anek text-sm">Les codes sont envoyés aux journalistes et coachs référencés.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-12">
            <!-- Retour à l'accueil -->
            <a href="<?php echo $base_url; ?>/index.php" 
                class="inline-flex items-center px-6 py-3 bg-rouge text-white font-bebas text-lg rounded-lg tracking-wide hover:bg-rouge/90 transition-colors duration-200 mr-4">
                RETOUR ACCUEIL
            </a>
            <!-- Voir les candidats pour voter -->
            <a href="<?php echo $base_url; ?>/pages/candidats.php" 
                class="inline-flex items-center px-6 py-3 bg-transparent text-bleu border-2 border-bleu font-bebas text-lg rounded-lg tracking-wide hover:bg-bleu hover:text-white transition-colors duration-200">
                VOIR CANDIDATS
            </a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>