<?php

namespace App\Controllers;

use App\Core\Render;
use App\Models\Page;

class Base
{

    public function index(): void
    {
        $pageModel = new Page();
        // Only show published pages on the public homepage
        $pages = $pageModel->getAll(true);

        $render = new Render("home", "frontoffice");
        $render->assign("pages", $pages);
        $render->render();
    }

    public function contact(): void
    {
        echo "Base contact";
    }


    public function portfolio(): void
    {
        echo "Base portfolio";
    }

}