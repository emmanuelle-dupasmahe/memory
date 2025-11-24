<?php
// Fichier : app/Controllers/ScoreController.php

namespace App\Controllers;

use App\Models\ScoreModel;
use Core\BaseController; 

class ScoreController extends BaseController
{
    private ScoreModel $scoreModel;

    public function __construct()
    {
        $this->scoreModel = new ScoreModel();
    }

    /**
     * Affiche le classement des 10 meilleurs joueurs.
     */
    public function leaderboard(): void
    {
        // Récupère les 10 meilleurs joueurs du modèle
        $topScores = $this->scoreModel->getLeaderboard(10);

        // Affiche la vue 'score/leaderboard.php'
        $this->render('score/leaderboard', [
            'title' => 'Classement Global',
            'topScores' => $topScores 
        ]);
    }

    /**
     * Affiche le profil détaillé d'un joueur.
     * Donne la priorité à l'utilisateur connecté (via $_SESSION)
     * puis utilise le paramètre GET (pour consulter d'autres profils).
     */
    public function profile(): void
    {
        $username = null;

        // 1. Priorité : Récupérer le nom d'utilisateur connecté (via la session)
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
        } 
        
        // 2. Sinon : Vérifier le paramètre GET dans l'URL (pour consulter un autre profil)
        // Note : On ne remplace la session que si le paramètre GET est présent et différent.
        if (isset($_GET['username']) && !empty($_GET['username'])) {
             $username = $_GET['username'];
        }

        // 3. Vérification finale
        if (!$username) {
             // Si l'utilisateur n'est ni connecté, ni ne fournit de nom dans l'URL, on le renvoie à l'inscription
             header('Location: /register');
             exit;
        }
        
        // 4. Récupérer les données du joueur via le Modèle
        $playerData = $this->scoreModel->getPlayerProfile($username);

        if (!$playerData) {
            // Gérer le cas où l'utilisateur n'existe pas
            // Nous utilisons un 404 car l'URL demandée n'a pas pu être satisfaite
            $this->render('home/404', [
                'title' => 'Joueur introuvable',
                'message' => "Joueur '{$username}' non trouvé."
            ]);
            return;
        }

        // 5. Afficher la vue 'score/profile.php'
        $this->render('score/profile', [
            'title' => 'Profil de ' . htmlspecialchars($username),
            'profile' => $playerData
        ]);
    }
}