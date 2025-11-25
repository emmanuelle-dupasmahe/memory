<?php
// Fichier : app/Controllers/GameController.php

namespace App\Controllers;

use Core\BaseController;
use App\Models\GameModel;
use App\Models\ScoreModel; 
use App\Entities\Card; // Assurez-vous d'importer l'entité Card

class GameController extends BaseController
{
    private GameModel $gameModel;
    private ScoreModel $scoreModel;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->gameModel = new GameModel();
        // Assurez-vous que le ScoreModel existe !
        $this->scoreModel = new ScoreModel(); 
    }

    /**
     * Affiche la page de sélection du nombre de paires.
     * Route: GET /game
     */
    public function index(): void
    {
        $this->render('game/options', ['title' => 'Démarrer la Partie']);
    }

    /**
     * Gère la sélection du nombre de paires et démarre une nouvelle partie.
     * Route: POST /game/start
     */
    public function start(): void
    {
        // CORRECTION DU PROBLÈME 1 : Changement de 'num_pairs' à 'pairs' pour correspondre à game/options.php
        $numPairs = isset($_POST['pairs']) ? (int)$_POST['pairs'] : 6;
        
        $numPairs = max(3, min(12, $numPairs));
        
        $this->gameModel->initializeBoard($numPairs);

        // Rediriger vers la page de jeu réelle (/game/index)
        header('Location: /game/index');
        exit;
    }

    /**
     * Affiche le plateau de jeu et gère l'état de la partie.
     * Route: GET /game/index
     */
    public function play(): void
    {
        // 1. Initialisation et récupération des données du jeu
        if (!isset($_SESSION['memory_board'])) {
            header('Location: /game');
            exit;
        }

        $board = $this->gameModel->getBoard();
        $turns = $this->gameModel->getTurns();
        
        // Récupération et suppression du message flash
        $message = $_SESSION['game_message'] ?? null;
        unset($_SESSION['game_message']);
        
        // 2. Préparation du tableau de données pour la vue (UTILISATION DE LA MÉTHODE toArray())
        $boardData = [];
        /** @var Card $card */
        foreach ($board as $card) {
            // Conversion de l'objet Card en tableau associatif
            $boardData[] = $card->toArray();
        }

        // 3. Logique de Fin de Partie
        if ($this->gameModel->isGameOver()) {
            
            // CORRECTION DU PROBLÈME 2 : Utilisation de l'identifiant String (Nom d'utilisateur ou ID)
            $scoreIdentifier = $_SESSION['username'] ?? $_SESSION['user_id'] ?? 'Anonyme';
            
            if ($scoreIdentifier !== 'Anonyme') {
                // ATTENTION: saveScore DOIT accepter une string (le nom d'utilisateur) ou string|int.
                // Sinon, l'erreur TypeError réapparaîtra si ScoreModel.php n'est pas corrigé.
                $this->scoreModel->saveScore($scoreIdentifier, $turns, $this->gameModel->getPairsCount()); 
            }
            
            $this->render('game/game_over', [ 
                'turns' => $turns,
                'title' => 'Partie Terminée !',
                'pairs_count' => $this->gameModel->getPairsCount(),
                'message' => "Félicitations ! Vous avez gagné en $turns coups !"
            ]);
            // Destruction de la session de jeu après la fin pour forcer un nouveau départ
            unset($_SESSION['memory_board']);
            unset($_SESSION['memory_flipped']);
            unset($_SESSION['memory_matched']);
            unset($_SESSION['memory_turns']);
            return;
        }

        // 4. Rendre la vue du jeu
        $this->render('game/index', [
            'board' => $boardData, // ENVOI DES TABLEAUX ASSOCIATIFS CORRECTS
            'turns' => $turns,
            'title' => 'Jeu de Mémoire',
            'pairs_count' => $this->gameModel->getPairsCount(),
            'message' => $message,
            'canClick' => count($_SESSION['memory_flipped'] ?? []) < 2
        ]);
    }


    /**
     * Gère le retournement d'une carte.
     * Route: POST /game/flip
     */
    public function flip(): void
    {
        $boardId = (int)($_POST['board_id'] ?? null);
        
        if ($boardId === null) {
            header('Location: /game/index');
            exit;
        }

        $flippedCount = count($_SESSION['memory_flipped'] ?? []);
        
        if ($flippedCount >= 2) {
            header('Location: /game/index');
            exit;
        }
        
        $this->gameModel->flipCard($boardId); 
        
        header('Location: /game/index');
        exit;
    }

    /**
     * Vérifie si les deux cartes retournées forment une paire et réinitialise l'état.
     * Route: POST /game/check
     */
    public function checkAndReset(): void 
    {
        $isMatch = $this->gameModel->checkMatch(); 
        
        if (!$isMatch) {
            $this->gameModel->unflipAll(); 
            $_SESSION['game_message'] = "Dommage ! Pas de paire trouvée.";
        } else {
            $_SESSION['game_message'] = "Bravo ! Paire trouvée !";
        }
        
        header('Location: /game/index');
        exit;
    }
}