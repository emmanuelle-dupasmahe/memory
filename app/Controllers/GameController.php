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

        if (empty($_SESSION['memory_board'])) {
             // Redirige vers la nouvelle partie si la session est vide
             $this->gameModel->initializeBoard();
             // Optionnel : Rediriger après initialisation pour charger la page 'propre'
             // header('Location: /game');
             // exit;
        }
        // 1. Récupérer l'état du plateau. Initialise si c'est la première visite.
        $board = $this->gameModel->getBoard();
        $turns = $this->gameModel->getTurns();
        
        // Convertir les objets Card en tableaux pour la vue, si nécessaire
        $boardData = array_map(fn($card) => $card->toArray(), $board);

        // 2. Charger la vue (Nous supposerons que votre Router appelle la méthode 'render' de BaseController)
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
        header('Location: /game/index'); 
        exit;
    }

    public function flip(): void
{
    // Récupérer l'ID de la carte cliquée (via un formulaire POST)
    $boardId = (int)($_POST['board_id'] ?? null);
    
    if ($boardId === null) {
        // Rediriger vers la page d'accueil si l'ID est manquant
        header('Location: /game');
        exit;
    }

    // --- ÉTAPE 1 : Gérer l'état après le clic ---
    $message = null;
    $flippedCount = count($_SESSION['memory_flipped'] ?? []);
    
    // Si nous avons déjà 2 cartes retournées et qu'une nouvelle carte est cliquée
    if ($flippedCount === 2) {
        // On doit d'abord vérifier et réinitialiser l'état précédent.
        $this->gameModel->checkMatch(); // Met à jour le plateau (match/unflip)
    }

    // Maintenant, retourner la nouvelle carte
    $this->gameModel->flipCard($boardId);
    
    // --- ÉTAPE 2 : Vérifier le nouvel état ---
    
    // On vérifie si DEUX cartes sont maintenant retournées après ce clic
    if (count($_SESSION['memory_flipped'] ?? []) === 2) {
        
        // Simuler le délai : Nous allons marquer le fait qu'il y a un match PENDANT une requête
        // et laisser l'utilisateur voir le résultat. La VUE doit afficher un bouton "Continuer"
        // qui déclenchera la vérification réelle et la réinitialisation des cartes non trouvées.
        
        if ($this->gameModel->isGameOver()) {
            // **Partie terminée !**
            $turns = $this->gameModel->getTurns();
            // TODO: Récupérer l'ID utilisateur (doit être stocké dans la session après connexion)
            $userId = $_SESSION['user_id'] ?? 1; 
            $this->scoreModel->saveScore($userId, $turns);
            $message = "Partie terminée ! Vous avez gagné en $turns tours.";
            // La vue affichera le message et le lien vers le classement.
        }
        
        // Si c'est la 2ème carte cliquée, nous ne faisons RIEN d'autre dans cette requête.
        // On laisse la page se recharger pour montrer les deux cartes au joueur.
        
        // C'est à la vue d'afficher les deux cartes et potentiellement un bouton "Continuer".

    }

    // Afficher la page de jeu mise à jour
    $this->renderGameView($message);
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
        // Si 2 cartes sont retournées, nous bloquons les autres clics.
        'canClick' => count($_SESSION['memory_flipped'] ?? []) < 2
    ]);
}
    public function checkAndReset(): void 
{
    // Vérifie si les cartes correspondent (dans le modèle)
    $isMatch = $this->gameModel->checkMatch(); 
    
    if (!$isMatch) {
        // Si ce n'est pas un match, le modèle va les masquer (unflip)
        $this->gameModel->unflipAll(); 
    }
    // Redirige pour afficher le plateau mis à jour
    header('Location: /game');
    exit;
}
}


