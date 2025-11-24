<?php
// Fichier : app/Controllers/GameController.php

namespace App\Controllers;

use App\Models\GameModel;
use App\Models\ScoreModel; // Nécessaire pour l'enregistrement du score en fin de partie
use Core\BaseController; 

class GameController extends BaseController // Adapter l'héritage à votre structure
{
    private GameModel $gameModel;
    private ScoreModel $scoreModel;

    public function __construct()
    {
        // Assurez-vous d'inclure les fichiers et de démarrer la session si nécessaire
        $this->gameModel = new GameModel();
        $this->scoreModel = new ScoreModel();
    }

    /**
     * Affiche la grille de jeu actuelle.
     */
    public function index(): void
    {
        
//session_destroy();
        if (empty($_SESSION['memory_board'])) {
               
             // Redirige vers la nouvelle partie si la session est vide
             $this->gameModel->initializeBoard();
             // Optionnel : Rediriger après initialisation pour charger la page 'propre'
             // header('Location: /game');
             // exit;
        }
        // Récupérer l'état du plateau. Initialise si c'est la première visite.
        $board = $this->gameModel->getBoard();
    
        $turns = $this->gameModel->getTurns();

    //    DEBUG CODE
    //     echo "<pre>";
    //     var_dump($board);
    //     echo "</pre>";
    //     exit;

        // Convertir les objets Card en tableaux pour la vue, si nécessaire
        $boardData = array_map(fn($card) => $card->toArray(), $board);

        // Charger la vue 
        $this->render('game/index', [
            'board' => $boardData,
            'turns' => $turns,
            'isGameOver' => $this->gameModel->isGameOver()
        ]);
    }

    /**
     * Démarre une nouvelle partie.
     */
    public function newGame(): void
    {
        $this->gameModel->initializeBoard();
        // Redirige vers la page de jeu
        header('Location: /game'); 
        exit;
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


