<?php
namespace App\Controllers;

use App\Core\Render;
use App\Models\User;

class UserController {
    
    public function index() {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? 'user') !== 'admin') {
            header("Location: /login");
            exit;
        }

        $model = new User();
        $users = $model->getAll();

        $render = new Render("User/list", "backoffice");
        $render->assign("users", $users);
        $render->render();
    }

    public function create() {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? 'user') !== 'admin') {
            header("Location: /login");
            exit;
        }
        
        $message = "";
        $oldFirstname = "";
        $oldLastname = "";
        $oldEmail = "";
        $oldRole = 'user';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = ucwords(strtolower(trim($_POST['firstname'] ?? '')));
            $lastname = strtoupper(trim($_POST['lastname'] ?? ''));
            $email = strtolower(trim($_POST['email'] ?? ''));
            $role = in_array($_POST['role'] ?? 'user', ['user','admin']) ? $_POST['role'] : 'user';
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';

            $oldFirstname = $firstname;
            $oldLastname = $lastname;
            $oldEmail = $email;
            $oldRole = $role;

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
                $model = new User();
                if ($model->getByEmail($email)) {
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
                $model = new User();
                $model->create($firstname, $lastname, $email, $password, $role);
                header("Location: /admin/users");
                exit;
            } else {
                $message = implode("<br>", $errors);
            }
        }

        $render = new Render("User/create", "backoffice");
        $render->assign("message", $message);
        $render->assign("oldFirstname", $oldFirstname);
        $render->assign("oldLastname", $oldLastname);
        $render->assign("oldEmail", $oldEmail);
        $render->assign("oldRole", $oldRole);
        $render->render();
    }

    public function edit() {
        if (!isset($_SESSION['user'])) header("Location: /login");

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /admin/users");
            exit;
        }

        $model = new User();
        $user = $model->getById($id);

        if (!$user) {
            http_response_code(404);
            die("Utilisateur non trouvé");
        }

        $message = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = ucwords(strtolower(trim($_POST['firstname'] ?? '')));
            $lastname = strtoupper(trim($_POST['lastname'] ?? ''));
            $email = strtolower(trim($_POST['email'] ?? ''));
            $is_active = isset($_POST['is_active']) ? true : false;
            $role = in_array($_POST['role'] ?? 'user', ['user','admin']) ? $_POST['role'] : 'user';

            $errors = [];

            if (strlen($firstname) < 2) {
                $errors[] = "Le prénom doit faire au moins 2 caractères";
            }

            if (strlen($lastname) < 2) {
                $errors[] = "Le nom doit faire au moins 2 caractères";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Le format de l'email est invalide";
            } else if ($email !== $user['email']) {
                if ($model->getByEmail($email)) {
                    $errors[] = "L'email existe déjà";
                }
            }

            if (empty($errors)) {
                $model->update($id, $firstname, $lastname, $email, $is_active, $role);
                header("Location: /admin/users");
                exit;
            } else {
                $message = implode("<br>", $errors);
            }

            $user['firstname'] = $firstname;
            $user['lastname'] = $lastname;
            $user['email'] = $email;
            $user['is_active'] = $is_active;
        }

        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? 'user') !== 'admin') {
            header("Location: /login");
            exit;
        }

        $render = new Render("User/edit", "backoffice");
        $render->assign("message", $message);
        $render->assign("user", $user);
        $render->render();
    }

    public function delete() {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? 'user') !== 'admin') {
            header("Location: /login");
            exit;
        }

        if(isset($_GET['id'])){
            $model = new User();
            $model->delete($_GET['id']);
        }
        header("Location: /admin/users");
    }
}