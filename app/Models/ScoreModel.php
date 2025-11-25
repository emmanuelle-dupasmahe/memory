<?php
// Fichier : app/Models/ScoreModel.php

namespace App\Models;

use Core\Database; 
use PDO;

class ScoreModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }
    
    /**
     * Enregistre le score en utilisant le nom d'utilisateur et récupère l'ID numérique.
     * Le paramètre $boardSize est ignoré ici car non présent dans la table scores.
     */
    public function saveScore(string $username, int $turns, int $boardSize = 0): bool
    {
        // 1. Récupérer l'ID numérique (u.id) à partir du nom d'utilisateur
        $userStmt = $this->pdo->prepare("SELECT id FROM users WHERE username = :username");
        $userStmt->execute(['username' => $username]);
        $userId = $userStmt->fetchColumn();

        if (!$userId) {
            // Si l'utilisateur n'est pas trouvé dans la table 'users', on n'enregistre pas le score.
            error_log("Tentative d'enregistrement de score pour un utilisateur inconnu: $username");
            return false;
        }

        // 2. Enregistrement du score dans la table 'scores'
        // Nous enregistrons l'ID numérique trouvé ($userId)
        $stmt = $this->pdo->prepare("INSERT INTO scores (user_id, coups, date_partie) VALUES (:user_id, :coups, NOW())");
        return $stmt->execute([
            'user_id' => $userId,
            'coups' => $turns // Le $turns de PHP correspond au 'coups' de la BDD
        ]);
    }

    // Affichage du Classement (Leaderboard) 
    public function getLeaderboard(int $limit = 10): array
    {
        // Utilisation des tables 'users' et de la colonne 'coups'
        $query = "
            SELECT 
                u.username, 
                MIN(s.coups) AS best_score, 
                COUNT(s.id) AS games_played
            FROM scores s
            JOIN users u ON s.user_id = u.id
            GROUP BY u.id, u.username
            ORDER BY best_score ASC
            LIMIT :limit
        ";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    // Affichage du Profil Individuel 
    public function getPlayerProfile(string $username): array|false
    {
        // 1. Récupérer l'utilisateur dans la table 'users'
        // Nous allons optimiser cette requête pour récupérer uniquement les informations de l'utilisateur
        $userStmt = $this->pdo->prepare("SELECT id, username FROM users WHERE username = :username");
        $userStmt->execute(['username' => $username]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        // 2. Récupérer tous ses scores, en utilisant 'coups' et 'date_partie'
        $scoresStmt = $this->pdo->prepare("SELECT coups, date_partie FROM scores WHERE user_id = :user_id ORDER BY date_partie DESC");
        $scoresStmt->execute(['user_id' => $user['id']]);
        $allScores = $scoresStmt->fetchAll(PDO::FETCH_ASSOC);


        // 3. Calculer les statistiques 
        $bestScore = PHP_INT_MAX;
        $gamesPlayed = count($allScores);

        if ($gamesPlayed > 0) {
            // Utiliser la colonne 'coups' pour trouver le minimum
            $bestScore = min(array_column($allScores, 'coups')); 
        }

        return [
            'username' => $user['username'],
            'best_score' => $bestScore === PHP_INT_MAX ? 'N/A' : $bestScore,
            'games_played' => $gamesPlayed,
            'scores_history' => $allScores
        ];
    }
}