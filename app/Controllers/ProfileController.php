<?php
// On suppose que ce fichier agit comme le point d'entrée pour la page de profil.

// 1. Démarrer la session pour accéder aux données de connexion
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- LOGIQUE D'AUTHENTIFICATION ET DE CHARGEMENT DU MODÈLE ---

// 2. Vérifier si l'utilisateur est connecté et si son nom d'utilisateur est en session
if (!isset($_SESSION['username'])) {
    // Si l'utilisateur n'est pas connecté, le rediriger vers la page de connexion
    header('Location: login.php');
    exit;
}

$currentUsername = $_SESSION['username'];

// 3. Charger le modèle nécessaire (Adaptez ce 'require' à la structure de votre projet)
// Si vous utilisez l'autochargement (autoloading), ce require n'est pas nécessaire.
require_once 'app/Models/ScoreModel.php'; 
require_once 'core/Database.php'; // Nécessaire pour initialiser la connexion PDO dans le modèle

// 4. Initialiser le Modèle et récupérer les données
$scoreModel = new \App\Models\ScoreModel();
$profile = $scoreModel->getPlayerProfile($currentUsername);

// S'assurer que les données sont bien présentes (l'utilisateur existe et a des infos)
if ($profile === false) {
    // Cas où l'utilisateur est connecté en session mais n'existe pas dans la BDD (très rare)
    $profile = [
        'username' => $currentUsername,
        'best_score' => 'N/A',
        'games_played' => 0,
        'scores_history' => []
    ];
}

// 5. Inclure la vue (profile.php) :
// C'est à ce moment que la variable $profile devient disponible pour la vue.
require 'views/profile.php'; 
// Note : Le code que vous m'avez fourni est le contenu de ce fichier 'views/profile.php'

?>