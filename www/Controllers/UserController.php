<?php
namespace App\Controllers;

use App\Core\Render;
use App\Models\User;

class UserController {
    
    public function index() {
        if (!isset($_SESSION['user'])) header("Location: /login");

        $model = new User();
        $users = $model->getAll();

        $render = new Render("User/list", "backoffice");
        $render->assign("users", $users);
        $render->render();
    }

    public function delete() {
        if (!isset($_SESSION['user'])) header("Location: /login");

        if(isset($_GET['id'])){
            $model = new User();
            $model->delete($_GET['id']);
        }
        header("Location: /admin/users");
    }
}