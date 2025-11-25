<?php

namespace App\Controllers;

use Core\BaseController;
use App\Models\ScoreModel; // On importe votre modèle de score
use App\Models\UserModel;

class AuthController extends BaseController
{
    private ScoreModel $scoreModel;
    private UserModel $userModel;

    public function __construct()
    {
        // On initialise le modèle pour interroger la base de données
        // (Nécessite que Core\Database soit accessible via le ScoreModel)
        $this->scoreModel = new ScoreModel(); 
        $this->userModel = new UserModel();
    }

      // Affiche la vue du formulaire
    public function showRegisterForm(): void
    {
        $this->render('auth/register'); // Utilise la même vue
    }

    // Traite la soumission du formulaire
    public function register(): void
    {
        if (empty($_POST['username'])) {
            $this->render('auth/register', ['error' => 'Le nom d\'utilisateur ne peut pas être vide.']);
            return;
        }

        $username = trim($_POST['username']);

        // // on vérifie si le nom d'utilisateur existe déjà
        // if ($this->userModel->findUserByUsername($username)) {
        //     $this->render('auth/register', ['error' => 'Ce nom d\'utilisateur est déjà pris.']);
        //     return;
        // }

        // on créé l'utilisateur
        try {
            $userId = $this->userModel->createUser($username);
            
            if ($userId) {
                // Connexion automatique et stockage en session
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $username;
                
                // Redirection vers le jeu
                header('Location: /game');
                exit;
            } else {
                $this->render('auth/register', ['error' => 'Erreur lors de l\'enregistrement.']);
            }

        } catch (\PDOException $e) {
             // Gérer les erreurs de BDD spécifiques, si besoin
             $this->render('auth/register', ['error' => 'Erreur de base de données.']);
        }
    }
    public function showProfile(): void
    {
        // 1. Démarrer la session (BaseController pourrait déjà le faire, mais sécurité)
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Vérifier l'authentification
        if (!isset($_SESSION['username'])) {
            // Rediriger vers la page d'accueil ou de connexion si non connecté
            header('Location: /login'); 
            exit;
        }

        $currentUsername = $_SESSION['username'];

        // 3. Charger les données du profil via le Modèle
        // Nous utilisons la méthode getPlayerProfile que vous avez fournie dans ScoreModel.php
        $profile = $this->scoreModel->getPlayerProfile($currentUsername);
        
        // Gérer le cas où le profil n'est pas trouvé (par exemple si l'utilisateur est supprimé de la BDD)
        if ($profile === false) {
             $profile = [
                'username' => $currentUsername,
                'best_score' => 'N/A',
                'games_played' => 0,
                'scores_history' => []
            ];
        }

        // 4. Passer la variable $profile à la vue
        // C'est cette ligne qui rend la variable $profile disponible dans votre fichier profile.php
        $this->render('profile', ['profile' => $profile]);
    }
    public function logout(): void
    {
        // Démarrage de la session pour pouvoir la détruire
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Détruire les données de session spécifiques à l'utilisateur
        if (isset($_SESSION['user_id'])) {
            unset($_SESSION['user_id']);
        }
        if (isset($_SESSION['username'])) {
            unset($_SESSION['username']);
        }
        
        // Redirection vers la page d'accueil ou de connexion
        header('Location: /'); 
        exit;
    }
}
