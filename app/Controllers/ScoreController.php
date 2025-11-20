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
        $topPlayers = $this->scoreModel->getLeaderboard(10);

        // Affiche la vue 'score/leaderboard.php'
        $this->render('score/leaderboard', [
            'players' => $topPlayers
        ]);
    }

    /**
     * Affiche le profil détaillé d'un joueur.
     * Route : GET /profile?username={username}
     */
    public function profile(): void // Ne prend plus le paramètre en argument de méthode
    {
        // 1. Récupérer le nom d'utilisateur depuis l'URL (paramètre GET)
        $username = $_GET['username'] ?? null;

        if (!$username) {
            // Si le paramètre est manquant, rediriger ou afficher une erreur
            $this->render('home/404', ['message' => "Nom d'utilisateur manquant pour afficher le profil."]);
            return;
        }
        
        // 2. Récupérer les données du joueur via le Modèle
        $playerData = $this->scoreModel->getPlayerProfile($username);

        if (!$playerData) {
            // Gérer le cas où l'utilisateur n'existe pas
            $this->render('home/404', ['message' => "Joueur '{$username}' non trouvé."]);
            return;
        }

        // 3. Afficher la vue 'score/profile.php'
        $this->render('score/profile', [
            'profile' => $playerData
        ]);
    }
}