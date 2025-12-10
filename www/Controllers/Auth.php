<?php
namespace App\Controllers;

use App\Core\Render;
use App\Core\Database;
use App\Helpers\ValidationHelper;

class Auth
{
    public function login(): void
    {
        $message  = "";
        $oldEmail = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = strtolower(trim($_POST['email'] ?? ''));
            $password = $_POST['password'] ?? '';

            $oldEmail = $email;

            $errors = [];

            if ($email === '') {
                $errors[] = "L'email est obligatoire";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Le format de l'email est invalide";
            }

            if ($password === '') {
                $errors[] = "Le mot de passe est obligatoire";
            }

            if (empty($errors)) {
                $pdo = Database::getInstance()->getConnection();

                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
                $stmt->execute([':email' => $email]);
                $user = $stmt->fetch();

                if (!$user) {
                    $errors[] = "Identifiants incorrects.";
                } elseif (!password_verify($password, $user['pwd'])) {
                    $errors[] = "Identifiants incorrects.";
                } elseif (!$user['is_active']) {
                    $errors[] = "Votre compte n'est pas encore activé. Consultez vos emails!";
                } else {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['user'] = [
                        'id'        => $user['id'],
                        'email'     => $user['email'],
                        'firstname' => $user['firstname'],
                        'lastname'  => $user['lastname'],
                        'role'      => $user['role'] ?? 'user',
                    ];

                    header('Location: /');
                    exit;
                }
            }

            if (!empty($errors)) {
                $message = implode("<br>", $errors);
            }
        }

        $render = new Render("login", "frontoffice");
        $render->assign("message", $message);
        $render->assign("oldEmail", $oldEmail);
        $render->render();
    }

    public function register(): void
    {
        $message       = "";
        $oldFirstname  = "";
        $oldLastname   = "";
        $oldEmail      = "";
        $success       = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname        = ValidationHelper::cleanFirstname($_POST['firstname'] ?? '');
            $lastname         = ValidationHelper::cleanLastname($_POST['lastname'] ?? '');
            $email            = ValidationHelper::cleanEmail($_POST['email'] ?? '');
            $password         = $_POST['password'] ?? '';
            $passwordConfirm  = $_POST['password_confirm'] ?? '';

            $oldFirstname = $firstname;
            $oldLastname  = $lastname;
            $oldEmail     = $email;

            $errors = [];

            if (!ValidationHelper::validateMinLength($firstname, 2)) {
                $errors[] = "Le prénom doit faire au moins 2 caractères";
            }

            if (!ValidationHelper::validateMinLength($lastname, 2)) {
                $errors[] = "Le nom doit faire au moins 2 caractères";
            }

            if (!ValidationHelper::validateEmail($email)) {
                $errors[] = "Le format de l'email est invalide";
            } else {
                $pdo = Database::getInstance()->getConnection();
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
                $stmt->execute([':email' => $email]);

                if ($stmt->fetch()) {
                    $errors[] = "L'email existe déjà";
                }
            }

            if (!ValidationHelper::validatePassword($password)) {
                $errors[] = "Le mot de passe doit faire au moins 8 caractères avec une minuscule, une majuscule, un chiffre et un caractère spécial";
            }

            if ($password !== $passwordConfirm) {
                $errors[] = "Le mot de passe de confirmation ne correspond pas";
            }

            if (empty($errors)) {
                $pdo = Database::getInstance()->getConnection();
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                
                // Générer token d'activation
                $verificationToken = bin2hex(random_bytes(32));
                $tokenExpiry = date('Y-m-d H:i:s', strtotime('+1 day'));

                $insert = $pdo->prepare(
                    "INSERT INTO users (firstname, lastname, email, pwd, role, is_active, verification_token, token_expiry, created_at)
                     VALUES (:firstname, :lastname, :email, :pwd, :role, :is_active, :verification_token, :token_expiry, NOW())"
                );

                $insert->bindValue(':firstname', $firstname, \PDO::PARAM_STR);
                $insert->bindValue(':lastname', $lastname, \PDO::PARAM_STR);
                $insert->bindValue(':email', $email, \PDO::PARAM_STR);
                $insert->bindValue(':pwd', $passwordHash, \PDO::PARAM_STR);
                $insert->bindValue(':role', 'user', \PDO::PARAM_STR);
                $insert->bindValue(':is_active', false, \PDO::PARAM_BOOL);
                $insert->bindValue(':verification_token', $verificationToken, \PDO::PARAM_STR);
                $insert->bindValue(':token_expiry', $tokenExpiry, \PDO::PARAM_STR);
                
                $ok = $insert->execute();

                if ($ok) {

                    $emailSent = \App\Helpers\EmailHelper::sendActivation($email, $firstname, $verificationToken);
                    
                    if ($emailSent) {
                        $success = true;
                        $message = "Inscription réussie ! Un email d'activation a été envoyé à $email";
                    } else {

                        $activationLink = "http://" . $_SERVER['HTTP_HOST'] . "/activate?token=" . $verificationToken;
                        $success = true;
                        $message = "Inscription réussie ! Lien d'activation : <a href='$activationLink'>$activationLink</a>";
                    
                    }
                } else {
                    $errors[] = "Erreur lors de l'inscription";
                }
            }

            if (!empty($errors)) {
                $message = implode("<br>", $errors);
            }
        }

        $render = new Render("register", "frontoffice");
        $render->assign("message", $message);
        $render->assign("success", $success);
        $render->assign("oldFirstname", $oldFirstname);
        $render->assign("oldLastname", $oldLastname);
        $render->assign("oldEmail", $oldEmail);
        $render->render();
    }

    public function activate(): void
    {
        $message = "";
        $success = false;
        $token = $_GET['token'] ?? '';

        if ($token === '') {
            $message = "Token d'activation manquant";
        } else {
            $pdo = Database::getInstance()->getConnection();
            
            $stmt = $pdo->prepare(
                "SELECT id, email, firstname, is_active, token_expiry 
                 FROM users 
                 WHERE verification_token = :token 
                 LIMIT 1"
            );
            $stmt->execute([':token' => $token]);
            $user = $stmt->fetch();

            if (!$user) {
                $message = "Lien d'activation invalide";
            } elseif ($user['is_active']) {
                $message = "Votre compte est déjà activé";
                $success = true;
            } elseif ($user['token_expiry'] && strtotime($user['token_expiry']) < time()) {
                $message = "Ce lien a expiré (valable 24h)";
            } else {
                $activate = $pdo->prepare(
                    "UPDATE users 
                     SET is_active = :is_active, 
                         verification_token = NULL, 
                         token_expiry = NULL,
                         updated_at = NOW() 
                     WHERE id = :id"
                );
                
                $activate->bindValue(':is_active', true, \PDO::PARAM_BOOL);
                $activate->bindValue(':id', $user['id'], \PDO::PARAM_INT);
                $activate->execute();
                
                $success = true;
                $message = "Compte activé Wouhou! Vous pouvez vous connecter ;)";
                header('Refresh: 3; url=/login');
            }
        }

        $render = new Render("activation", "frontoffice");
        $render->assign("message", $message);
        $render->assign("success", $success);
        $render->render();
    }

    public function forgotPassword(): void
    {
        $message = "";
        $oldEmail = "";
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = strtolower(trim($_POST['email'] ?? ''));
            $oldEmail = $email;

            $errors = [];

            if ($email === '') {
                $errors[] = "L'email est obligatoire";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Le format de l'email est invalide";
            }

            if (empty($errors)) {
                $pdo = Database::getInstance()->getConnection();
                $stmt = $pdo->prepare("SELECT id, firstname FROM users WHERE email = :email LIMIT 1");
                $stmt->execute([':email' => $email]);
                $user = $stmt->fetch();

                if ($user) {
                    $token = bin2hex(random_bytes(32));
                    
                    $update = $pdo->prepare(
                        "INSERT INTO password_resets (user_id, token, created_at)
                        VALUES (:user_id, :token, NOW())"
                    );

                    $update->execute([
                        ':user_id' => $user['id'],
                        ':token'   => $token
                    ]);

                    $emailSent = \App\Helpers\EmailHelper::sendPasswordReset($email, $user['firstname'], $token);
                    
                    $success = true;
                    if ($emailSent) {
                        $message = "Un email de réinitialisation a été envoyé à $email";
                    } else {
                        $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/reset-password?token=" . $token;
                        $message = "Lien de réinitialisation : <a href='$resetLink'>$resetLink</a>";
                    }
                    
                    $oldEmail = "";
                } else {
                    $success = true;
                    $message = "Si cet email existe, un lien a été envoyé.";
                    $oldEmail = "";
                }
            }

            if (!empty($errors)) {
                $message = implode("<br>", $errors);
            }
        }

        $render = new Render("forgot_password", "frontoffice");
        $render->assign("message", $message);
        $render->assign("success", $success);
        $render->assign("oldEmail", $oldEmail);
        $render->render();
    }

    public function resetPassword(): void
    {
        $message = "";
        $success = false;
        $token = $_GET['token'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            $token = $_POST['token'] ?? '';

            $errors = [];

            if (!ValidationHelper::validatePassword($password)) {
                $errors[] = "Le mot de passe doit faire au moins 8 caractères avec une minuscule, une majuscule, un chiffre et un caractère spécial";
            }

            if ($password !== $passwordConfirm) {
                $errors[] = "Le mot de passe de confirmation ne correspond pas";
            }

            if (empty($errors)) {
                $pdo = Database::getInstance()->getConnection();

                $stmt = $pdo->prepare(
                    "SELECT user_id 
                    FROM password_resets 
                    WHERE token = :token 
                    AND created_at > NOW() - INTERVAL '15 minutes'
                    LIMIT 1"
                );
                $stmt->execute([':token' => $token]);
                $reset = $stmt->fetch();

                if (!$reset) {
                    $errors[] = "Lien invalide ou expiré (15 min max)";
                } else {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                    $updateStmt = $pdo->prepare(
                        "UPDATE users SET pwd = :pwd, updated_at = NOW() WHERE id = :user_id"
                    );
                    $updateStmt->execute([
                        ':pwd' => $passwordHash,
                        ':user_id' => $reset['user_id'],
                    ]);

                    $cleanToken = $pdo->prepare(
                        "DELETE FROM password_resets WHERE user_id = :user_id"
                    );
                    $cleanToken->execute([':user_id' =>  $reset['user_id']]);

                    $success = true;
                    $message = "Mot de passe changé ! Redirection......................";
                    header('Refresh: 2; url=/login');
                }
            }

            if (!empty($errors)) {
                $message = implode("<br>", $errors);
            }
        } else {
            if ($token === '') {
                $message = "Token manquant";
            } else {
                $pdo = Database::getInstance()->getConnection();
                
                $stmt = $pdo->prepare(
                    "SELECT user_id 
                    FROM password_resets 
                    WHERE token = :token 
                    AND created_at > NOW() - INTERVAL '15 minutes'
                    LIMIT 1"
                );
                $stmt->execute([':token' => $token]);
                $reset = $stmt->fetch();

                if (!$reset) {
                    $message = "Lien invalide ou expiré";
                }
            }
        }

        $render = new Render("reset_password", "frontoffice");
        $render->assign("message", $message);
        $render->assign("success", $success);
        $render->assign("token", $token);
        $render->render();
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();

        header('Location: /login');
        exit;
    }
}