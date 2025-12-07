<?php
namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailHelper
{
    private static function getMailer(): PHPMailer
    {
        $mail = new PHPMailer(true);
        
        // Configure SMTP
        $mail->isSMTP();
        $mail->Host       = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('SMTP_USER');
        $mail->Password   = getenv('SMTP_PASSWORD');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = getenv('SMTP_PORT') ?: 587;
        
        // De...
        $mail->setFrom(
            getenv('SMTP_FROM_EMAIL'),
            getenv('SMTP_FROM_NAME') ?: 'Mini WordPress'
        );
        
        $mail->CharSet = 'UTF-8';
        
        return $mail;
    }
    

    public static function sendActivation(string $email, string $firstname, string $token): bool
    {
        try {
            $mail = self::getMailer();
            
            $mail->addAddress($email, $firstname);
            $mail->Subject = 'Activez votre compte';
            
            $activationLink = "http://" . $_SERVER['HTTP_HOST'] . "/activate?token=" . $token;
            
            $mail->isHTML(true);
            $mail->Body = "
                <h2>Bonjour $firstname,</h2>
                <p>Merci de vous être inscrit !</p>
                <p>Cliquez sur le lien ci-dessous pour activer votre compte :</p>
                <p><a href='$activationLink' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Activer mon compte</a></p>
                <p>Ou copiez ce lien : <br>$activationLink</p>
                <p><small>Ce lien expire dans 24 heures.</small></p>
            ";
            
            $mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Erreur email: " . $e->getMessage());
            return false;
        }
    }

    
    public static function sendPasswordReset(string $email, string $firstname, string $token): bool
    {
        try {
            $mail = self::getMailer();
            
            $mail->addAddress($email, $firstname);
            $mail->Subject = 'Réinitialisation de mot de passe';
            
            $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/reset-password?token=" . $token;
            
            $mail->isHTML(true);
            $mail->Body = "
                <h2>Bonjour $firstname,</h2>
                <p>Vous avez demandé à réinitialiser votre mot de passe.</p>
                <p>Cliquez sur le lien ci-dessous :</p>
                <p><a href='$resetLink' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Réinitialiser</a></p>
                <p>Ou copiez ce lien : <br>$resetLink</p>
                <p><small>Ce lien expire dans 15 minutes.</small></p>
            ";
            
            $mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Erreur email: " . $e->getMessage());
            return false;
        }
    }
}