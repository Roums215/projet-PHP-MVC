<?php
namespace App\Controllers;

use App\Core\Render;
use App\Core\Database;

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
            $firstname        = ucwords(strtolower(trim($_POST['firstname'] ?? '')));
            $lastname         = strtoupper(trim($_POST['lastname'] ?? ''));
            $email            = strtolower(trim($_POST['email'] ?? ''));
            $password         = $_POST['password'] ?? '';
            $passwordConfirm  = $_POST['password_confirm'] ?? '';

            $oldFirstname = $firstname;
            $oldLastname  = $lastname;
            $oldEmail     = $email;

            $errors = [];

            if (strlen($firstname) < 2) {
                $errors[] = "Le prénom doit faire au moins 2 caractères";
            }

            if (strlen($lastname) < 2) {
                $errors[] = "Le nom doit faire au moins 2 caractères";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Le format de l'email est invalide";
            } else {
                $pdo = Database::getInstance()->getConnection();
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
                $stmt->execute([':email' => $email]);

                if ($stmt->fetch()) {
                    $errors[] = "L'email existe déjà";
                }
            }

            if (strlen($password) < 8 ||
                !preg_match('#[A-Z]#', $password) ||
                !preg_match('#[a-z]#', $password) ||
                !preg_match('#[0-9]#', $password)
            ) {
                $errors[] = "Le mot de passe doit faire au moins 8 caractères avec une minuscule, une majuscule et un chiffre";
            }

            if ($password !== $passwordConfirm) {
                $errors[] = "Le mot de passe de confirmation ne correspond pas";
            }

            if (empty($errors)) {
                $pdo = Database::getInstance()->getConnection();
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $insert = $pdo->prepare(
                    "INSERT INTO users (firstname, lastname, email, pwd, role, is_active, created_at)
                     VALUES (:firstname, :lastname, :email, :pwd, :role, :is_active, NOW())"
                );

                $ok = $insert->execute([
                    ':firstname' => $firstname,
                    ':lastname'  => $lastname,
                    ':email'     => $email,
                    ':pwd'       => $passwordHash,
                    ':role'      => 'user',
                    ':is_active' => true,
                ]);

                if ($ok) {
                    $success = true;
                    $message = "Inscription réussie ! Redirection vers la connexion...";
                    header('Refresh: 2; url=/login');
                } else {
                    $errors[] = "Erreur technique lors de l'inscription";
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
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
                $stmt->execute([':email' => $email]);
                $user = $stmt->fetch();

                if ($user) {
                    $token = bin2hex(random_bytes(32));
                    $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

                    $insertToken = $pdo->prepare(
                        "INSERT INTO password_resets (user_id, token, created_at)
                         VALUES (:user_id, :token, NOW())"
                    );

                    $insertToken->execute([
                        ':user_id' => $user['id'],
                        ':token'   => $token,
                    ]);

                    $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/reset-password?token=" . $token;
                    
                    // Ici, vous pouvez envoyer un email avec le lien
                    // Pour le moment, on affiche juste le message
                    $success = true;
                    $message = "Un lien de réinitialisation a été envoyé à votre email (en dev: $resetLink)";
                    $oldEmail = "";
                } else {
                    // On ne révèle pas si l'email existe ou non (sécurité)
                    $success = true;
                    $message = "Si cet email existe, un lien de réinitialisation a été envoyé";
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

            if (strlen($password) < 8 ||
                !preg_match('#[A-Z]#', $password) ||
                !preg_match('#[a-z]#', $password) ||
                !preg_match('#[0-9]#', $password)
            ) {
                $errors[] = "Le mot de passe doit faire au moins 8 caractères avec une minuscule, une majuscule et un chiffre";
            }

            if ($password !== $passwordConfirm) {
                $errors[] = "Le mot de passe de confirmation ne correspond pas";
            }

            if (empty($errors)) {
                $pdo = Database::getInstance()->getConnection();

                // Vérifier que le token existe et n'est pas expiré
                $stmt = $pdo->prepare(
                    "SELECT user_id FROM password_resets 
                     WHERE token = :token 
                     AND created_at > NOW() - INTERVAL '1 hour'"
                );
                $stmt->execute([':token' => $token]);
                $reset = $stmt->fetch();

                if (!$reset) {
                    $errors[] = "Lien de réinitialisation invalide ou expiré";
                } else {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                    // Mettre à jour le mot de passe
                    $updateStmt = $pdo->prepare(
                        "UPDATE users SET pwd = :pwd, updated_at = NOW() WHERE id = :user_id"
                    );
                    $updateStmt->execute([
                        ':pwd' => $passwordHash,
                        ':user_id' => $reset['user_id'],
                    ]);

                    // Supprimer le token
                    $deleteStmt = $pdo->prepare("DELETE FROM password_resets WHERE token = :token");
                    $deleteStmt->execute([':token' => $token]);

                    $success = true;
                    $message = "Mot de passe réinitialisé avec succès ! Redirection vers la connexion...";
                    header('Refresh: 2; url=/login');
                }
            }

            if (!empty($errors)) {
                $message = implode("<br>", $errors);
            }
        } else {
            // Vérifier que le token est valide
            if ($token === '') {
                $message = "Token manquant";
            } else {
                $pdo = Database::getInstance()->getConnection();
                $stmt = $pdo->prepare(
                    "SELECT user_id FROM password_resets 
                     WHERE token = :token 
                     AND created_at > NOW() - INTERVAL '1 hour'"
                );
                $stmt->execute([':token' => $token]);
                $reset = $stmt->fetch();

                if (!$reset) {
                    $message = "Lien de réinitialisation invalide ou expiré";
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