<?php 
/** * @var string $title
 * @var string $message
 */

// Récupère le titre s'il a été passé, sinon utilise un titre par défaut
$title = $title ?? "Erreur 404"; 

// Récupère le message spécifique (souvent passé par le contrôleur), sinon utilise un message par défaut
$errorMessage = $message ?? "La page que vous recherchez est introuvable.";
?>

<h1><?= htmlspecialchars($title) ?></h1>

<div class="error-container">
    <p>
        Désolé, mais une erreur est survenue.
    </p>
    
    <p class="error-message">
        <strong>Détail :</strong> <?= htmlspecialchars($errorMessage) ?>
    </p>

    <div class="actions">
        <a href="/" class="button-home">Retour à l'accueil 🏠</a>
        <a href="/game" class="button-game">Recommencer à jouer 🎮</a>
    </div>
</div>

<style>
    .error-container {
        max-width: 600px;
        margin: 50px auto;
        padding: 30px;
        border: 1px solid #ddd;
        border-radius: 8px;
        text-align: center;
        background-color: #fff;
    }
    .error-container h1 {
        color: #d32f2f; /* Rouge */
        font-size: 2.5em;
    }
    .error-message {
        margin: 20px 0;
        padding: 15px;
        background-color: #ffebee; /* Fond très clair */
        border: 1px dashed #d32f2f;
        border-radius: 4px;
    }
    .actions a {
        display: inline-block;
        margin: 0 10px;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    .button-home {
        background-color: #6a0dad;
        color: white;
    }
    .button-home:hover {
        background-color: #55008d;
    }
</style>