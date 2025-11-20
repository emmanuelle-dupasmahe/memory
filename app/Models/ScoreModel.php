<?php
// Fichier : app/Models/ScoreModel.php

namespace App\Models;

use Core\Database; // On utilise la classe Database que vous venez de montrer
use App\Entities\Player; // Pour hydrater l'objet Player
use PDO;

class ScoreModel
{
    private PDO $pdo;

    public function __construct()
    {
        // Utilisation du Singleton pour récupérer l'instance PDO
        $this->pdo = Database::getPdo();
    }

    // --- 1. Gestion des Utilisateurs (Connexion/Inscription simplifiée) ---

    /**
     * Trouve un utilisateur par son nom d'utilisateur ou le crée s'il n'existe pas.
     */
    public function findOrCreateUser(string $username): array|false
    {
        // 1. Chercher l'utilisateur existant
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user) {
            return $user; // Utilisateur trouvé
        }

        // 2. Si non trouvé, créer le nouvel utilisateur (avec un mot de passe fictif/simple pour l'exemple)
        $hashedPassword = password_hash('memory_default', PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $success = $stmt->execute(['username' => $username, 'password' => $hashedPassword]);

        if ($success) {
            // Retourner l'ID du nouvel utilisateur
            return ['id' => $this->pdo->lastInsertId()];
        }

        return false;
    }

    // --- 2. Enregistrement des Scores ---

    /**
     * Enregistre le score (nombre de coups) d'une partie terminée.
     */
    public function saveScore(int $userId, int $turns): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO scores (user_id, coups, date_partie) VALUES (:user_id, :coups, NOW())");
        return $stmt->execute([
            'user_id' => $userId,
            'coups' => $turns
        ]);
    }

    // --- 3. Affichage du Classement (Leaderboard) ---

    /**
     * Récupère le classement des 10 meilleurs joueurs (basé sur le MINIMUM de coups).
     */
    public function getLeaderboard(int $limit = 10): array
    {
        // Jointure pour obtenir le username et le meilleur score de chaque joueur
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
        
        return $stmt->fetchAll(); // Retourne un tableau des meilleurs joueurs
    }

    // --- 4. Affichage du Profil Individuel ---

    /**
     * Récupère les scores détaillés et les statistiques d'un joueur spécifique.
     */
    public function getPlayerProfile(string $username): array|false
    {
        // 1. Récupérer l'utilisateur
        $userStmt = $this->pdo->prepare("SELECT id, username FROM users WHERE username = :username");
        $userStmt->execute(['username' => $username]);
        $user = $userStmt->fetch();

        if (!$user) {
            return false;
        }

        // 2. Récupérer tous ses scores, triés par date (du plus récent au plus ancien)
        $scoresStmt = $this->pdo->prepare("SELECT coups, date_partie FROM scores WHERE user_id = :user_id ORDER BY date_partie DESC");
        $scoresStmt->execute(['user_id' => $user['id']]);
        $allScores = $scoresStmt->fetchAll();

        // 3. Calculer les statistiques agrégées
        $bestScore = PHP_INT_MAX;
        $gamesPlayed = count($allScores);
        if ($gamesPlayed > 0) {
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