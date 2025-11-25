<?php
// Fichier : app/Models/GameModel.php

namespace App\Models;

use App\Entities\Card;
use Core\Database;
use PDO;

class GameModel {
    private PDO $pdo; 
    
    // Clés de session
    private const SESSION_KEY = 'memory_board'; 
    private const TURNS_KEY = 'memory_turns';
    private const FLIPPED_KEY = 'memory_flipped'; 
    private const PAIRS_COUNT_KEY = 'memory_pairs_count'; // Nouvelle clé pour stocker le nombre de paires

    public function __construct() {
        $this->pdo = Database::getPdo();
    }

    // Ajouté pour récupérer le nombre de paires actuel
    public function getPairsCount(): int
    {
        // Retourne le nombre de paires de la partie en cours, 6 par défaut
        return $_SESSION[self::PAIRS_COUNT_KEY] ?? 6;
    }

    /**
     * Récupère la liste des types de cartes depuis la base de données.
     */
    private function getCardTypesFromDatabase(): array {
        $stmt = $this->pdo->query("SELECT id, nom, image_recto FROM cartes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Initialise ou réinitialise un nouveau plateau de jeu de cartes.
     * @param int $numPairs Le nombre de paires à utiliser (entre 3 et 12).
     */
    public function initializeBoard(int $numPairs = 6): void {

        // 1. Définir le nombre de paires, avec limites de sécurité
        $numPairs = max(3, min(12, $numPairs)); 

        $allCardTypes = $this->getCardTypesFromDatabase();
        
        // 2. Sélectionner aléatoirement le nombre de types de cartes requis
        shuffle($allCardTypes);
        $selectedCardTypes = array_slice($allCardTypes, 0, $numPairs);

        // 3. Créer les paires 
        $boardData = array_merge($selectedCardTypes, $selectedCardTypes);

        // 4. Mélanger les cartes
        shuffle($boardData);

        // 5. Hydrater la liste d'objets Card et les stocker en session
        $board = [];
        foreach ($boardData as $index => $data) {
            $board[$index] = new Card(
                $index, // boardId
                $data['id'], // typeId
                $data['nom'],
                $data['image_recto']
            ); 
        }
    
        // 6. Réinitialiser les variables de session et stocker le nombre de paires (maintenant dans la fonction)
        $_SESSION[self::SESSION_KEY] = serialize($board);
        $_SESSION[self::TURNS_KEY] = 0;
        $_SESSION[self::FLIPPED_KEY] = [];
        $_SESSION[self::PAIRS_COUNT_KEY] = $numPairs; // Enregistre le choix
    } 

    /**
     * Retourne le plateau de jeu (liste d'objets Card) depuis la session.
     */
    public function getBoard(): array {

        // Si le plateau n'existe pas, on initialise avec la valeur par défaut de 6 paires
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $this->initializeBoard();
        }
        
        $board = unserialize($_SESSION[self::SESSION_KEY]); 

        // Vérifie si la désérialisation a échoué 
        if ($board === false || !is_array($board)) {
            error_log("Avertissement: Données de session du plateau corrompues. Réinitialisation.");
            $this->initializeBoard();

            // On récupère le nouveau plateau initialisé
            $board = unserialize($_SESSION[self::SESSION_KEY]);
            if ($board === false || !is_array($board)) {
                return [];
            }
        }

        return $board; 
    } 

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
        
        // Vérifier si l'ID est valide et si la carte n'est pas déjà visible ou trouvée
        if (!isset($board[$boardId]) || $board[$boardId]->isFlipped()) {
            return $this->isGameOver();
        }

        /** @var Card $card */
        $card = $board[$boardId];
        
        // Si moins de 2 cartes sont retournées, on retourne celle-ci
        if (count($flippedIds) < 2) {
            $card->flip();
            $flippedIds[] = $boardId;
            $_SESSION[self::FLIPPED_KEY] = $flippedIds;
            $_SESSION[self::SESSION_KEY] = serialize($board);//serialize

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

        $board = unserialize($_SESSION[self::SESSION_KEY]);//unserialize
        
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
        }

        $_SESSION[self::SESSION_KEY] = serialize($board);//serialize
        $_SESSION[self::FLIPPED_KEY] = []; // Réinitialiser pour la prochaine tentative
        
        return $isMatch;
    }
    
    /**
     * Retourne toutes les cartes qui ne sont pas trouvées.
     */
    public function unflipAll(): void {
        $board = unserialize($_SESSION[self::SESSION_KEY]);//unserialize
        
        /** @var Card $card */
        foreach ($board as $card) {
            $card->unflip();
        }
        $_SESSION[self::SESSION_KEY] = serialize($board);//serialize
        $_SESSION[self::FLIPPED_KEY] = [];
    }

    /**
     * Vérifie si toutes les cartes ont été trouvées.
     */
    public function isGameOver(): bool {
        if (!isset($_SESSION[self::SESSION_KEY])) return false;
        //var_dump($_SESSION[self::SESSION_KEY]);
        //exit;
        $board = unserialize($_SESSION[self::SESSION_KEY]);//unserialize
        /** @var Card $card */
        // On itère sur la variable $board désérialisée
        foreach ($board as $card) { 
            if (!$card->isMatched()) {
                return false;
            }
        }
        return true;
    }
}