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
     * Affiche le profil détaillé d'un joueur (ex: /score/profile/Alice).
     */
    public function profile(string $username): void
    {
        // Récupère les données de progression du joueur
        $playerData = $this->scoreModel->getPlayerProfile($username);

        if (!$playerData) {
            // Gérer le cas où l'utilisateur n'existe pas (affichage d'une erreur)
            $this->render('home/404', ['message' => "Joueur '{$username}' non trouvé."]);
            return;
        }

        // Affiche la vue 'score/profile.php'
        $this->render('score/profile', [
            'profile' => $playerData
        ]);
    }
}