<?php
namespace App\Controllers;

use App\Core\Render;
use App\Models\Page;

class PageController {
    

    public function index() {
        if (!isset($_SESSION['user'])) header("Location: /login");
        
        $model = new Page();
        $pages = $model->getAll();
        
        $render = new Render("Page/list", "backoffice");
        $render->assign("pages", $pages);
        $render->render();
    }



    public function create() {
        if (!isset($_SESSION['user'])) header("Location: /login");
        $message = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];

            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

            $model = new Page();
            $model->create($title, $slug, $content);
            
            header("Location: /admin/pages");
            exit;
        }

        $render = new Render("Page/add", "backoffice");
        $render->assign("message", $message);
        $render->render();
    }


    public function delete() {
        if (!isset($_SESSION['user'])) header("Location: /login");
        
        if(isset($_GET['id'])){
            $model = new Page();
            $model->delete($_GET['id']);
        }
        header("Location: /admin/pages");
    }


    public function show($slug) {
        $model = new Page();
        $page = $model->getBySlug($slug);

        if(!$page) {
            die("ERROR 404");
        }

        $render = new Render("Page/show", "frontoffice");
        $render->assign("page", $page);
        $render->render();
    }
}