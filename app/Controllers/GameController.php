<?php
namespace App\Controllers;

use App\Models\ArticleModel;
use Core\BaseController;

class ArticleController extends BaseController {
    public function index() {
        $articles = new ArticleModel();

        $data = [
            "title"=> "Mes articles DWWM",
            "articles" => $articles -> all()
        ];

        $this-> render("article/index", $data);
    }
}