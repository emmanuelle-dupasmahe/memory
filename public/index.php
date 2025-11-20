<?php


require_once __DIR__ ."/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__. "/../");
$dotenv->safeLoad();

// Importation des classes avec namespaces pour éviter les conflits de noms
use Core\Router;

// Initialisation du routeur
$router = new Router();

// Définition des routes de l'application
// La route "/" pointe vers la méthode "index" du contrôleur HomeController
//$router->get('/', 'App\\Controllers\\HomeController@index');

//$router->get('/about', 'App\\Controllers\\HomeController@about');

// La route "/articles" pointe vers la méthode "index" du contrôleur ArticleController
//$router->get('/articles', 'App\\Controllers\\ArticleController@index');



// Affichage et Initialisation
$router->get('/game', 'App\\Controllers\\GameController@index');
$router->get('/game/new', 'App\\Controllers\\GameController@newGame');

// Actions de jeu (POST : pour les formulaires sans JS)
$router->post('/game/flip', 'App\\Controllers\\GameController@flip');
$router->post('/game/checkAndReset', 'App\\Controllers\\GameController@checkAndReset');

// --- Routes des Scores (ScoreController) ---

// Classement global
$router->get('/leaderboard', 'App\\Controllers\\ScoreController@leaderboard');

// Profil individuel (utilisation du paramètre GET pour le nom d'utilisateur)
$router->get('/profile', 'App\\Controllers\\ScoreController@profile');


// Exécution du routeur :
// On analyse l'URI et la méthode HTTP pour appeler le contrôleur et la méthode correspondants
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);