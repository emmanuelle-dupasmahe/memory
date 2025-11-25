<?php
// Fichier : app/Controllers/GameController.php

namespace App\Controllers;

use Core\BaseController;
use App\Models\GameModel;
use App\Models\ScoreModel; // Nécessaire pour sauvegarder le score

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
        $this->scoreModel = new ScoreModel(); // Assurez-vous d'avoir ce modèle
    }

    /**
     * Affiche la page de sélection du nombre de paires.
     * C'est la nouvelle page d'accueil du jeu.
     */
    public function index(): void
    {
        $this->render('game/options', ['title' => 'Démarrer la Partie']);
    }

    /**
     * Gère la sélection du nombre de paires et démarre une nouvelle partie.
     */
    public function start(): void
    {
        // Récupérer le nombre de paires depuis le POST ou utiliser 6 par défaut
        $numPairs = isset($_POST['pairs']) ? (int)$_POST['pairs'] : 6;
        
        // Assurer que la valeur est dans les bornes autorisées
        $numPairs = max(3, min(12, $numPairs));
        
        // Initialiser le plateau avec le choix de l'utilisateur
        $this->gameModel->initializeBoard($numPairs);

        // Rediriger vers la page de jeu réelle (play)
        header('Location: /game/play');
        exit;
    }

    /**
     * Affiche le plateau de jeu et gère l'état de la partie.
     * C'est l'ancienne méthode 'game' renommée 'play'.
     */
    public function play(): void
    {
        // 1. Initialisation si nécessaire (ici, elle est faite par le modèle si la session est vide)
        $board = $this->gameModel->getBoard();
        $turns = $this->gameModel->getTurns();
        
        // Vérification de la fin de partie (peut se produire après un 'flip' réussi)
        if ($this->gameModel->isGameOver()) {
            
            // On vérifie si l'utilisateur est connecté pour enregistrer le score
            $username = $_SESSION['username'] ?? null;
            if ($username) {
                // Enregistrement du score si la partie est terminée
                $this->scoreModel->saveScore($username, $turns); 
            }
            
            // On propose de rejouer
            $this->render('game/game_over', [
                'turns' => $turns,
                'title' => 'Partie Terminée !',
                'pairs_count' => $this->gameModel->getPairsCount()
            ]);
            return;
        }

        // Rendre la vue du jeu
        $this->render('game/play', [
            'board' => $board,
            'turns' => $turns,
            'title' => 'Jeu de Mémoire',
            'pairs_count' => $this->gameModel->getPairsCount() // Afficher la difficulté
        ]);
    }


public function flip(): void
{
    // AJOUTEZ CETTE LIGNE
    $boardId = (int)($_POST['board_id'] ?? null);
    
    // Assurez-vous que l'ID est valide avant de continuer
    if ($boardId === null) {
        // Optionnel : Gérer l'erreur si l'ID est manquant
        header('Location: /game');
        exit;
    }

    $flippedCount = count($_SESSION['memory_flipped'] ?? []);
    
    // Si nous avons déjà 2 cartes, le joueur n'a pas cliqué sur "Continuer"
    // On ignore le clic pour forcer le joueur à utiliser le bouton "Continuer".
    if ($flippedCount >= 2) {
        header('Location: /game');
        exit;
    }
    
    // Si on arrive ici, on retourne la carte (premier ou deuxième clic)
    $this->gameModel->flipCard($boardId); // <-- C'est ici qu'il était utilisé !
    
    // Après le flip, on affiche la vue mise à jour (qui va potentiellement montrer le bouton "Continuer")
    $this->renderGameView();
}

private function renderGameView(?string $message = null): void 
{
    $board = $this->gameModel->getBoard();
    $boardData = array_map(fn($card) => $card->toArray(), $board);
    $turns = $this->gameModel->getTurns();

    $this->render('game/index', [
        'board' => $boardData,
        'turns' => $turns,
        'isGameOver' => $this->gameModel->isGameOver(),
        'message' => $message,
        // Si 2 cartes sont retournées, on bloque les autres clics.
        'canClick' => count($_SESSION['memory_flipped'] ?? []) < 2
    ]);
}
    public function checkAndReset(): void 
{
    $message = null; // Initialisation pour la vue

    // 1. Finaliser le tour (vérifie le match, incrémente les tours, masque les cartes si nécessaire)
    $isMatch = $this->gameModel->checkMatch(); 
    
    if (!$isMatch) {
        $this->gameModel->unflipAll(); 
    }
    
    // 2. Vérification de Fin de Partie et Sauvegarde (AJOUT NÉCESSAIRE)
    if ($this->gameModel->isGameOver()) {
        $turns = $this->gameModel->getTurns();
        
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            // C'est ici que l'enregistrement se fait !
            $this->scoreModel->saveScore($userId, $turns); 
            $message = "Partie terminée ! Score enregistré en $turns coups !";
            // OPTIONNEL : $this->gameModel->clearBoard(); 
        } else {
            $message = "Partie terminée ! Vous avez gagné en $turns coups. Inscrivez-vous pour enregistrer.";
        }
    }
    
    // 3. Afficher la page de jeu mise à jour avec le message
    $this->renderGameView($message);

}
}


