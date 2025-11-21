<?php
namespace App\Models;

use Core\Database;
use PDO;
class UserModel
{
    private \PDO $pdo; 

    public function __construct()
    {
        
        $this->pdo = Database::getPdo(); 
    }
    /**
     * Crée un utilisateur avec juste un nom d'utilisateur.
     * @return int|bool L'ID du nouvel utilisateur ou false en cas d'échec.
     */
    public function createUser(string $username): int|bool
    {
        $sql = "INSERT INTO users (username) VALUES (:username)";
        
        $stmt = $this->pdo->prepare($sql);
        
        if ($stmt->execute(['username' => $username])) {
            return $this->pdo->lastInsertId(); // Retourne l'ID créé
        }
        return false;
    }

    /**
     * Vérifie si un utilisateur existe déjà (pour la connexion ou l'enregistrement).
     */
    public function findUserByUsername(string $username): ?array
    {
        $sql = "SELECT id, username FROM users WHERE username = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $user ?: null;
    }
}