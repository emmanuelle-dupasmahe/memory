<?php
namespace App\Controllers;

use Core\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    private UserModel $userModel;

    public function __construct()
    {
        
        $this->userModel = new UserModel();
    }

    // Affiche la vue du formulaire
    public function showRegisterForm(): void
    {
        $this->render('auth/register'); // Utilise la même vue
    }

    public function logout(): void
    {
    // Détruit toutes les données de session (ou seulement les variables d'utilisateur)
    session_unset();
    session_destroy();
    
    // Assurez-vous de relancer la session si votre application en a besoin après
    session_start();
    
    // Redirige vers la page d'accueil ou d'inscription
    header('Location: /'); 
    exit;
    }

    // Traite la soumission du formulaire
    public function register(): void
    {
        if (empty($_POST['username'])) {
            $this->render('auth/register', ['error' => 'Le nom d\'utilisateur ne peut pas être vide.']);
            return;
        }

        $username = trim($_POST['username']);

        // on vérifie si le nom d'utilisateur existe déjà
        //if ($this->userModel->findUserByUsername($username)) {
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
}