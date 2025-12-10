<?php
namespace App\Controllers;

use App\Core\Render;
use App\Models\Page;
use App\Helpers\ValidationHelper;

class PageController {
    

    public function index() {
        if (!ValidationHelper::isAdmin()) {
            header("Location: /");
            exit;
        }
        
        $model = new Page();
        $pages = $model->getAll();
        
        $render = new Render("Page/list", "backoffice");
        $render->assign("pages", $pages);
        $render->render();
    }

    public function create() {
        if (!ValidationHelper::isAdmin()) {
            header("Location: /");
            exit;
        }
        $message = "";
        $oldTitle = "";
        $oldContent = "";
        $oldSlug = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = $_POST['content'] ?? '';
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['slug'] ?? $title)));
            $is_published = isset($_POST['is_published']) ? true : false;

            $oldTitle = $title;
            $oldContent = $content;
            $oldSlug = $slug;

            $errors = [];

            if (!ValidationHelper::validateMinLength($title, 2)) {
                $errors[] = "Le titre doit faire au moins 2 caractères";
            }

            if (empty($content)) {
                $errors[] = "Le contenu est obligatoire";
            }

            if (!ValidationHelper::validateMinLength($slug, 2)) {
                $errors[] = "Le slug doit faire au moins 2 caractères";
            } else {
                $model = new Page();
                if ($model->slugExists($slug)) {
                    $errors[] = "Ce slug existe déjà";
                }
            }

            if (empty($errors)) {
                $model = new Page();
                $model->create($title, $slug, $content, $is_published);
                header("Location: /admin/pages");
                exit;
            } else {
                $message = implode("<br>", $errors);
            }
        }

        $render = new Render("Page/add", "backoffice");
        $render->assign("message", $message);
        $render->assign("oldTitle", $oldTitle);
        $render->assign("oldContent", $oldContent);
        $render->assign("oldSlug", $oldSlug);
        $render->assign("oldIsPublished", isset($is_published) ? $is_published : true);
        $render->render();
    }

    public function edit() {
        if (!ValidationHelper::isAdmin()) {
            header("Location: /");
            exit;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /admin/pages");
            exit;
        }

        $model = new Page();
        $page = $model->getById($id);

        if (!$page) {
            http_response_code(404);
            die("Page non trouvée");
        }

        $message = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = $_POST['content'] ?? '';
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['slug'] ?? '')));
            $is_published = isset($_POST['is_published']) ? true : false;

            $errors = [];

            if (!ValidationHelper::validateMinLength($title, 2)) {
                $errors[] = "Le titre doit faire au moins 2 caractères";
            }

            if (empty($content)) {
                $errors[] = "Le contenu est obligatoire";
            }

            if (!ValidationHelper::validateMinLength($slug, 2)) {
                $errors[] = "Le slug doit faire au moins 2 caractères";
            } else if ($slug !== $page['slug']) {
                if ($model->slugExists($slug, $id)) {
                    $errors[] = "Ce slug existe déjà";
                }
            }

            if (empty($errors)) {
                $model->update($id, $title, $slug, $content, $is_published);
                header("Location: /admin/pages");
                exit;
            } else {
                $message = implode("<br>", $errors);
            }

            $page['title'] = $title;
            $page['slug'] = $slug;
            $page['content'] = $content;
            $page['is_published'] = $is_published;
        }

        $render = new Render("Page/edit", "backoffice");
        $render->assign("message", $message);
        $render->assign("page", $page);
        $render->render();
    }

    public function delete() {
        if (!ValidationHelper::isAdmin()) {
            header("Location: /");
            exit;
        }
        
        if(isset($_GET['id'])){
            $model = new Page();
            $model->delete($_GET['id']);
        }
        header("Location: /admin/pages");
    }

    public function show($slug)
    {
        $isAdmin = isset($_SESSION['user']) && (($_SESSION['user']['role'] ?? 'user') === 'admin');
        $onlyPublished = !$isAdmin;

        $model = new Page();
        $page = $model->getBySlug($slug, $onlyPublished);

        if (!$page) {
            header("HTTP/1.0 404 Not Found");
            echo "Page non trouvée.";
            exit;
        }

        // rendre la vue
        $render = new Render("Page/show", "frontoffice");
        $render->assign("page", $page);
        $render->render();
    }

    /**
     * Liste les pages de l'utilisateur connecté
     */
    public function myPages() {
        if (!ValidationHelper::isAuthenticated()) {
            header("Location: /login");
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $model = new Page();
        $pages = $model->getByUserId($userId);

        $render = new Render("UserPages/list", "backoffice");
        $render->assign("pages", $pages);
        $render->render();
    }

    /**
     * Crée une page pour l'utilisateur connecté
     */
    public function createMyPage() {
        if (!ValidationHelper::isAuthenticated()) {
            header("Location: /login");
            exit;
        }

        $message = "";
        $oldTitle = "";
        $oldContent = "";
        $oldSlug = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = $_POST['content'] ?? '';
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['slug'] ?? $title)));
            $is_published = isset($_POST['is_published']) ? true : false;

            $oldTitle = $title;
            $oldContent = $content;
            $oldSlug = $slug;

            $errors = [];

            if (!ValidationHelper::validateMinLength($title, 2)) {
                $errors[] = "Le titre doit faire au moins 2 caractères";
            }

            if (empty($content)) {
                $errors[] = "Le contenu est obligatoire";
            }

            if (!ValidationHelper::validateMinLength($slug, 2)) {
                $errors[] = "Le slug doit faire au moins 2 caractères";
            } else {
                $model = new Page();
                if ($model->slugExists($slug)) {
                    $errors[] = "Ce slug existe déjà";
                }
            }

            if (empty($errors)) {
                $model = new Page();
                $userId = $_SESSION['user']['id'];
                $model->create($title, $slug, $content, $is_published, $userId);
                header("Location: /my-pages");
                exit;
            } else {
                $message = implode("<br>", $errors);
            }
        }

        $render = new Render("UserPages/add", "backoffice");
        $render->assign("message", $message);
        $render->assign("oldTitle", $oldTitle);
        $render->assign("oldContent", $oldContent);
        $render->assign("oldSlug", $oldSlug);
        $render->assign("oldIsPublished", isset($is_published) ? $is_published : true);
        $render->render();
    }

    /**
     * Modifie une page de l'utilisateur connecté
     */
    public function editMyPage() {
        if (!ValidationHelper::isAuthenticated()) {
            header("Location: /login");
            exit;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /my-pages");
            exit;
        }

        $model = new Page();
        $page = $model->getById($id);

        if (!$page) {
            http_response_code(404);
            die("Page non trouvée");
        }

        $userId = $_SESSION['user']['id'];
        
        // Vérifier que l'utilisateur est propriétaire de la page
        if (!$model->isOwner($id, $userId)) {
            http_response_code(403);
            die("Vous n'avez pas la permission de modifier cette page");
        }

        $message = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = $_POST['content'] ?? '';
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['slug'] ?? '')));
            $is_published = isset($_POST['is_published']) ? true : false;

            $errors = [];

            if (!ValidationHelper::validateMinLength($title, 2)) {
                $errors[] = "Le titre doit faire au moins 2 caractères";
            }

            if (empty($content)) {
                $errors[] = "Le contenu est obligatoire";
            }

            if (!ValidationHelper::validateMinLength($slug, 2)) {
                $errors[] = "Le slug doit faire au moins 2 caractères";
            } else if ($slug !== $page['slug']) {
                if ($model->slugExists($slug, $id)) {
                    $errors[] = "Ce slug existe déjà";
                }
            }

            if (empty($errors)) {
                $model->update($id, $title, $slug, $content, $is_published);
                header("Location: /my-pages");
                exit;
            } else {
                $message = implode("<br>", $errors);
            }

            $page['title'] = $title;
            $page['slug'] = $slug;
            $page['content'] = $content;
            $page['is_published'] = $is_published;
        }

        $render = new Render("UserPages/edit", "backoffice");
        $render->assign("message", $message);
        $render->assign("page", $page);
        $render->render();
    }

    /**
     * Supprime une page de l'utilisateur connecté
     */
    public function deleteMyPage() {
        if (!ValidationHelper::isAuthenticated()) {
            header("Location: /login");
            exit;
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $userId = $_SESSION['user']['id'];
            $model = new Page();

            // Vérifier que l'utilisateur est propriétaire de la page
            if ($model->isOwner($id, $userId)) {
                $model->delete($id);
            }
        }

        header("Location: /my-pages");
        exit;
    }
}