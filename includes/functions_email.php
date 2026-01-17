<?php
/**
 * Fonctions liées aux emails
 * Principe SOLID : Single Responsibility (gestion emails uniquement)
 */

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Envoyer email de création de compte
 */
function envoyerEmailCreationCompte($email, $mdpProvisoire, $prenom) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ahmed.errebache@gmail.com';
        $mail->Password = 'cnij ihjw zmbw qxyh'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        $mail->setFrom('votre-email@gmail.com', 'MMA Fighter Election');
        $mail->addAddress($email, $prenom);
        
        $mail->isHTML(true);
        $mail->Subject = 'Création de votre compte candidat';
        
        $lienFinalisation = BASE_URL . 'pages/candidat.php?action=finaliser';
        
        $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
        </head>
        <body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f3f4f6;">
            <div style="max-width:600px;margin:0 auto;">
                <div style="background:#1f2937;padding:30px;text-align:center;">
                    <h1 style="color:#fff;margin:0;font-size:24px;">MMA Fighter Election</h1>
                </div>
                
                <div style="padding:40px 30px;background:#fff;">
                    <h2 style="color:#1f2937;margin:0 0 20px 0;">Bonjour ' . htmlspecialchars($prenom) . ',</h2>
                    
                    <p style="color:#4b5563;line-height:1.6;margin:0 0 20px 0;">
                        Votre compte candidat a été créé. Voici vos identifiants de connexion :
                    </p>
                    
                    <div style="background:#f9fafb;padding:20px;border-radius:8px;margin:20px 0;">
                        <p style="margin:0 0 10px 0;color:#1f2937;"><strong>Email :</strong> ' . htmlspecialchars($email) . '</p>
                        <p style="margin:0;color:#1f2937;"><strong>Mot de passe provisoire :</strong> ' . htmlspecialchars($mdpProvisoire) . '</p>
                    </div>
                    
                    <p style="color:#4b5563;line-height:1.6;margin:20px 0;">
                        Cliquez sur le bouton ci-dessous pour finaliser votre compte.
                    </p>
                    
                    <div style="text-align:center;margin:30px 0;">
                        <a href="' . $lienFinalisation . '" style="background:#1f2937;color:#fff;padding:14px 32px;text-decoration:none;border-radius:6px;display:inline-block;font-weight:500;">
                            Finaliser mon compte
                        </a>
                    </div>
                </div>
                
                <div style="background:#f3f4f6;padding:20px;text-align:center;color:#6b7280;font-size:14px;">
                    <p style="margin:0;">© 2025 MMA Fighter Election</p>
                </div>
            </div>
        </body>
        </html>';
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur envoi email: {$mail->ErrorInfo}");
        return false;
    }
}

/**
 * Envoyer un code professionnel par email
 */
function envoyerCodeProfessionnel($email, $prenom, $nom, $code, $type) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ahmed.errebache@gmail.com';
        $mail->Password = 'cnij ihjw zmbw qxyh';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        $mail->setFrom('votre-email@gmail.com', 'MMA Fighter Election');
        $mail->addAddress($email, $prenom . ' ' . $nom);
        
        $mail->isHTML(true);
        $mail->Subject = 'Votre code professionnel - MMA Fighter Election';
        
        $typeLabel = ($type === 'journaliste') ? 'Journaliste' : 'Coach';
        
        $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
        </head>
        <body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f3f4f6;">
            <div style="max-width:600px;margin:0 auto;">
                <div style="background:#1f2937;padding:30px;text-align:center;">
                    <h1 style="color:#fff;margin:0;font-size:24px;">MMA Fighter Election</h1>
                </div>
                
                <div style="padding:40px 30px;background:#fff;">
                    <h2 style="color:#1f2937;margin:0 0 20px 0;">Bonjour ' . htmlspecialchars($prenom) . ',</h2>
                    
                    <p style="color:#4b5563;line-height:1.6;margin:0 0 20px 0;">
                        Votre code professionnel (' . $typeLabel . ') a été généré pour participer au vote.
                    </p>
                    
                    <div style="background:#f9fafb;padding:20px;border-radius:8px;margin:20px 0;text-align:center;">
                        <p style="margin:0 0 10px 0;color:#6b7280;font-size:14px;">Votre code unique :</p>
                        <p style="margin:0;color:#1f2937;font-size:24px;font-weight:bold;letter-spacing:2px;">' . htmlspecialchars($code) . '</p>
                    </div>
                    
                    <p style="color:#4b5563;line-height:1.6;margin:20px 0;">
                        Utilisez ce code lors de votre inscription sur la plateforme. Ce code ne peut être utilisé qu\'une seule fois.
                    </p>
                    
                    <div style="text-align:center;margin:30px 0;">
                        <a href="' . BASE_URL . 'pages/register.php" style="background:#1f2937;color:#fff;padding:14px 32px;text-decoration:none;border-radius:6px;display:inline-block;font-weight:500;">
                            S\'inscrire maintenant
                        </a>
                    </div>
                </div>
                
                <div style="background:#f3f4f6;padding:20px;text-align:center;color:#6b7280;font-size:14px;">
                    <p style="margin:0;">© 2025 MMA Fighter Election</p>
                </div>
            </div>
        </body>
        </html>';
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur envoi email code pro: {$mail->ErrorInfo}");
        return false;
    }
}
?>
