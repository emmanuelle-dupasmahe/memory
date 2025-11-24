<?php

namespace App\Controllers;

use Core\BaseController;
use App\Models\ScoreModel; // On importe votre modèle de score

class ProfileController extends BaseController
{
    private ScoreModel $scoreModel;

    public function __construct()
    {
        // On initialise le modèle pour interroger la base de données
        // (Nécessite que Core\Database soit accessible via le ScoreModel)
        $this->scoreModel = new ScoreModel(); 
    }

    public function showProfile(): void
    {
        // 1. Démarrer la session (BaseController pourrait déjà le faire, mais sécurité)
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Vérifier l'authentification
        if (!isset($_SESSION['username'])) {
            // Rediriger vers la page d'accueil ou de connexion si non connecté
            header('Location: /login'); 
            exit;
        }

        $currentUsername = $_SESSION['username'];

        // 3. Charger les données du profil via le Modèle
        // Nous utilisons la méthode getPlayerProfile que vous avez fournie dans ScoreModel.php
        $profile = $this->scoreModel->getPlayerProfile($currentUsername);
        
        // Gérer le cas où le profil n'est pas trouvé (par exemple si l'utilisateur est supprimé de la BDD)
        if ($profile === false) {
             $profile = [
                'username' => $currentUsername,
                'best_score' => 'N/A',
                'games_played' => 0,
                'scores_history' => []
            ];
        }

        // 4. Passer la variable $profile à la vue
        // C'est cette ligne qui rend la variable $profile disponible dans votre fichier profile.php
        $this->render('profile', ['profile' => $profile]);
    }
}