<?php

namespace App;
/*
 *
 * TP : Routing
 *
 * Faire en sorte que toutes les requêtes HTTP pointent sur le fichier index.php se trouvant dans public
 * Se baser ensuite sur le fichier routes.yml pour appeler la bonne classe dans le dossier controller et
 * la bonne methode (ce que l'on appel une action dans un controller)
 *
 * Exemple :
 * http://localhost:8080/contact
 * Doit créer une instance de Base et appeler la méthode (action) : contact
 * $controller = new Base();
 * $controller->contact();
 *
 * Pensez à effectuer tous les nettoyages et toutes les vérifications pour
 * afficher des erreurs (des simples die suffiront dans un premier temps)
 *
 * Rendu : Mail y.skrzypczyk@gmail.com
 * Objet du mail : 3IW1 - TP routing - Nom Prénom
 * Contenu du mail : fichier index.php et les autres fichiers créés s'il y en a
 *
 * Bon courage
 */


session_start(); 
//autoload.php pour phpmailer 
require_once __DIR__ . '/../vendor/autoload.php';  


spl_autoload_register(function ($class){
    $class = str_ireplace(["\\", "App"], ["/", ".."],$class);
    if(file_exists($class.".php")){
        include $class.".php";
    }
});


$requestUri = strtok($_SERVER["REQUEST_URI"], "?");
if(strlen($requestUri)>1)
    $requestUri = rtrim($requestUri, "/");
$requestUri = strtolower($requestUri);

$routes = yaml_parse_file("../routes.yml");


if(!empty($routes[$requestUri])){
    
    $controller = $routes[$requestUri]["controller"];
    $action = $routes[$requestUri]["action"];

    if(!file_exists("../Controllers/".$controller.".php")){
       die("Pas de controller pour cette uri");
    }

    include "../Controllers/".$controller.".php";
    
    $controllerName = "App\\Controllers\\".$controller;
    
    if(!class_exists($controllerName)){
       die("La classe du controller n'existe pas");
    }

    $objetController = new $controllerName();

    if(!method_exists($objetController, $action)){
       die("La methode du controller n'existe pas");
    }

    $objetController->$action();

} else {
    $slug = ltrim($requestUri, '/');
    $pageModel = new \App\Models\Page();

    $page = $pageModel->getBySlug($slug);

    if (!$page) {
        $candidate = $pageModel->getBySlug($slug, false);

        if ($candidate) {
            $isAdmin = isset($_SESSION['user']) && (($_SESSION['user']['role'] ?? 'user') === 'admin');
            $isAuthenticated = isset($_SESSION['user']);
            $userId = $isAuthenticated ? $_SESSION['user']['id'] : null;

            $isOwner = $userId ? $pageModel->isOwner($candidate['id'], $userId) : false;

            if ($candidate['is_published'] || $isAdmin || $isOwner) {
                $page = $candidate;
            }
        }
    }

    if ($page) {
        $controller = new \App\Controllers\PageController();

        $controller->show($slug);
        exit;
    }

    http_response_code(404);
    die("PAGE 404");
}