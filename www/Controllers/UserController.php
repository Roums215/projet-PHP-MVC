<?php
namespace App\Controllers;

use App\Core\Render;
use App\Models\User;
use App\Helpers\ValidationHelper;

class UserController {
    
    public function index() {
        if (!ValidationHelper::isAdmin()) {
            header("Location: /");
            exit;
        }

        $model = new User();
        $users = $model->getAll();

        $render = new Render("User/list", "backoffice");
        $render->assign("users", $users);
        $render->render();
    }

    public function create() {
        if (!ValidationHelper::isAdmin()) {
            header("Location: /");
            exit;
        }
        
        $message = "";
        $oldFirstname = "";
        $oldLastname = "";
        $oldEmail = "";
        $oldRole = 'user';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = ValidationHelper::cleanFirstname($_POST['firstname'] ?? '');
            $lastname = ValidationHelper::cleanLastname($_POST['lastname'] ?? '');
            $email = ValidationHelper::cleanEmail($_POST['email'] ?? '');
            $role = in_array($_POST['role'] ?? 'user', ['user','admin']) ? $_POST['role'] : 'user';
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';

            $oldFirstname = $firstname;
            $oldLastname = $lastname;
            $oldEmail = $email;
            $oldRole = $role;

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
                $model = new User();
                if ($model->getByEmail($email)) {
                    $errors[] = "L'email existe déjà";
                }
            }

            if (!ValidationHelper::validatePassword($password, false)) {
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
            $firstname = ValidationHelper::cleanFirstname($_POST['firstname'] ?? '');
            $lastname = ValidationHelper::cleanLastname($_POST['lastname'] ?? '');
            $email = ValidationHelper::cleanEmail($_POST['email'] ?? '');
            $is_active = isset($_POST['is_active']) ? true : false;
            $role = in_array($_POST['role'] ?? 'user', ['user','admin']) ? $_POST['role'] : 'user';

            $errors = [];

            if (!ValidationHelper::validateMinLength($firstname, 2)) {
                $errors[] = "Le prénom doit faire au moins 2 caractères";
            }

            if (!ValidationHelper::validateMinLength($lastname, 2)) {
                $errors[] = "Le nom doit faire au moins 2 caractères";
            }

            if (!ValidationHelper::validateEmail($email)) {
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

        if (!ValidationHelper::isAdmin()) {
            header("Location: /");
            exit;
        }

        $render = new Render("User/edit", "backoffice");
        $render->assign("message", $message);
        $render->assign("user", $user);
        $render->render();
    }

    public function delete() {
        if (!ValidationHelper::isAdmin()) {
            header("Location: /");
            exit;
        }

        if(isset($_GET['id'])){
            $model = new User();
            $model->delete($_GET['id']);
        }
        header("Location: /admin/users");
    }
}