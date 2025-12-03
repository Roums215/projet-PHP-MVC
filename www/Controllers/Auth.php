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
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $oldEmail = $email;


            if ($email === '' || $password === '') {
                $message = "email et mot de passe obligatoire";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = "email invalide";
            } else {

                $pdo = Database::getInstance()->getConnection();


                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
                $stmt->execute([':email' => $email]);
                $user = $stmt->fetch();


                if (!$user) {
                    $message = "Identifiants incorrects."; 
                } elseif (!password_verify($password, $user['pwd'])) {
                    $message = "Identifiants incorrects.";
                } elseif (isset($user['is_active']) && $user['is_active'] == false) {
                    $message = "compte pas activé";
                } else {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['user'] = [
                        'id'        => $user['id'],
                        'email'     => $user['email'],
                        'firstname' => $user['firstname'],
                        'lastname'  => $user['lastname'],
                    ];

                    header('Location: /');
                    exit;
                }
            }
        }

        $render = new Render("login", "backoffice");
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $firstname        = trim($_POST['firstname'] ?? '');
            $lastname         = trim($_POST['lastname'] ?? '');
            $email            = trim($_POST['email'] ?? '');
            $password         = $_POST['password'] ?? '';
            $passwordConfirm  = $_POST['password_confirm'] ?? '';

            $oldFirstname = $firstname;
            $oldLastname  = $lastname;
            $oldEmail     = $email;
            if ($firstname === '' || $lastname === '' || $email === '' || $password === '' || $passwordConfirm === '') {
                $message = "veuillez remplir tous les champs";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = "email invalide";
            } elseif ($password !== $passwordConfirm) {
                $message = "le mots de passe ne correspondent pas";
            } elseif (strlen($password) < 6) {
                $message = "mot de passe >= 6 caractères";
            } else {

                $pdo = Database::getInstance()->getConnection();

                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
                $stmt->execute([':email' => $email]);

                if ($stmt->fetch()) {
                    $message = "email deja utilisé";
                } else {

                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                    $insert = $pdo->prepare(
                        "INSERT INTO users (firstname, lastname, email, pwd, is_active, created_at)
                         VALUES (:firstname, :lastname, :email, :pwd, :is_active, NOW())"
                    );

                    $ok = $insert->execute([
                        ':firstname' => $firstname,
                        ':lastname'  => $lastname,
                        ':email'     => $email,
                        ':pwd'       => $passwordHash,
                        ':is_active' => true,
                    ]);

                    if ($ok) {
                        header('Location: /login');
                        exit;
                    } else {
                        $message = "ErrOr technique de l'inscription";
                    }
                }
            }
        }

        $render = new Render("register", "frontoffice");
        $render->assign("message", $message);
        $render->assign("oldFirstname", $oldFirstname);
        $render->assign("oldLastname", $oldLastname);
        $render->assign("oldEmail", $oldEmail);
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