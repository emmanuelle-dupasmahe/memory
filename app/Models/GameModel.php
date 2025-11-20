<?php
// Fichier : app/Models/GameModel.php

namespace App\Models;

use App\Entities\Card;
use Core\Database;
use PDO;

class GameModel {
    private PDO $pdo; // Connexion BDD pour récupérer les types de cartes
    
    // Clé de session où stocker le plateau de jeu
    private const SESSION_KEY = 'memory_board'; 
    private const TURNS_KEY = 'memory_turns';
    private const FLIPPED_KEY = 'memory_flipped'; // ID des cartes actuellement retournées (max 2)

    public function __construct() {
        // Assurez-vous que la session est démarrée avant l'instanciation du modèle
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->pdo = Database::getPdo();
    }

    /**
     * Récupère la liste des types de cartes depuis la base de données.
     */
    private function getCardTypesFromDatabase(): array {
        $stmt = $this->pdo->query("SELECT id, nom, image_recto FROM cartes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Initialise ou réinitialise un nouveau plateau de jeu de 12 cartes (6 paires).
     */
    public function initializeBoard(): void {
        $cardTypes = $this->getCardTypesFromDatabase(); // 6 types de cartes
        
        // 1. Créer les paires (doubler les 6 types)
        $boardData = array_merge($cardTypes, $cardTypes);
        
        // 2. Mélanger les cartes
        shuffle($boardData);

        // 3. Hydrater la liste d'objets Card et les stocker en session
        $board = [];
        foreach ($boardData as $index => $data) {
            $board[$index] = new Card(
                $index,              // boardId (position sur le plateau)
                $data['id'],         // typeId (ID du personnage pour la paire)
                $data['nom'],
                $data['image_recto']
            );
        }

        // Réinitialiser les variables de session
        $_SESSION[self::SESSION_KEY] = $board;
        $_SESSION[self::TURNS_KEY] = 0;
        $_SESSION[self::FLIPPED_KEY] = [];
    }
    
    /**
     * Retourne le plateau de jeu (liste d'objets Card) depuis la session.
     */
    public function getBoard(): array {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $this->initializeBoard();
        }
        return $_SESSION[self::SESSION_KEY];
    }
    
    /**
     * Récupère le nombre de tours joués.
     */
    public function getTurns(): int {
        return $_SESSION[self::TURNS_KEY] ?? 0;
    }

    // --- LOGIQUE DE JEU ---

    /**
     * Gère le retournement d'une carte et vérifie les paires.
     * @return bool Vrai si la partie est terminée.
     */
    public function flipCard(int $boardId): bool {
        $board = $this->getBoard();
        $flippedIds = $_SESSION[self::FLIPPED_KEY];
        
        // 1. Vérifier si l'ID est valide et si la carte n'est pas déjà visible ou trouvée
        if (!isset($board[$boardId]) || $board[$boardId]->isFlipped()) {
            return $this->isGameOver();
        }

        /** @var Card $card */
        $card = $board[$boardId];
        
        // 2. Si moins de 2 cartes sont retournées, on retourne celle-ci
        if (count($flippedIds) < 2) {
            $card->flip();
            $flippedIds[] = $boardId;
            $_SESSION[self::FLIPPED_KEY] = $flippedIds;
            $_SESSION[self::SESSION_KEY] = $board;

            // Si c'est la deuxième carte, on incrémente le nombre de tours
            if (count($flippedIds) === 2) {
                $_SESSION[self::TURNS_KEY]++;
            }
            
        } 
        
        // La vérification des paires et le retournement des cartes non trouvées
        // sera fait par le Contrôleur après cette requête, si count($flippedIds) == 2.

        return $this->isGameOver();
    }
    
    /**
     * Vérifie si les deux cartes actuellement retournées forment une paire.
     * @return bool Vrai si une paire a été trouvée.
     */
    public function checkMatch(): bool {
        $flippedIds = $_SESSION[self::FLIPPED_KEY];
        if (count($flippedIds) !== 2) return false;

        $board = $_SESSION[self::SESSION_KEY];
        
        /** @var Card $card1 */
        $card1 = $board[$flippedIds[0]];
        /** @var Card $card2 */
        $card2 = $board[$flippedIds[1]];

        $isMatch = ($card1->getTypeId() === $card2->getTypeId());

        if ($isMatch) {
            $card1->match();
            $card2->match();
        } else {
            // Si ce n'est pas une paire, elles ne sont pas retournées immédiatement
            // car l'utilisateur doit voir les deux cartes un instant.
            // Le Contrôleur devra appeler unflipAll après un court délai côté client.
        }

        $_SESSION[self::SESSION_KEY] = $board;
        $_SESSION[self::FLIPPED_KEY] = []; // Réinitialiser pour la prochaine tentative
        
        return $isMatch;
    }
    
    /**
     * Retourne toutes les cartes qui ne sont pas trouvées.
     */
    public function unflipAll(): void {
        $board = $_SESSION[self::SESSION_KEY];
        
        /** @var Card $card */
        foreach ($board as $card) {
            $card->unflip();
        }
        $_SESSION[self::SESSION_KEY] = $board;
        $_SESSION[self::FLIPPED_KEY] = [];
    }

    /**
     * Vérifie si toutes les cartes ont été trouvées.
     */
    public function isGameOver(): bool {
        if (!isset($_SESSION[self::SESSION_KEY])) return false;
        
        /** @var Card $card */
        foreach ($_SESSION[self::SESSION_KEY] as $card) {
            if (!$card->isMatched()) {
                return false;
            }
        }
        return true;
    }
}