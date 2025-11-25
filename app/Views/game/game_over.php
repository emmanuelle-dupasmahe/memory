<?php 
/**
 * Vue : game/game_over.php
 * Affiche les résultats de la partie une fois celle-ci terminée.
 *
 * Variables disponibles : 
 * - $title (string)
 * - $message (string)
 * - $turns (int) : Nombre de coups joués.
 * - $pairs_count (int) : Nombre de paires du plateau (pour contexte).
 */
?>

<div class="game-over-container">
    <h1><?= htmlspecialchars($title) ?></h1>
    
    <div class="message-box">
        <p class="congratulations"><?= htmlspecialchars($message) ?></p>
        
        <div class="stats">
            <p><strong>Difficulté :</strong> <?= htmlspecialchars($pairs_count) ?> paires (<?= htmlspecialchars($pairs_count * 2) ?> cartes)</p>
            <p class="score">Votre Score : <strong><?= htmlspecialchars($turns) ?></strong> coups</p>
        </div>

        <?php if (!empty($_SESSION['username'])): ?>
            <p class="score-info">Votre score a été enregistré sous le nom : <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>.</p>
        <?php else: ?>
            <p class="score-info">Connectez-vous pour que vos scores soient enregistrés et affichés dans le classement !</p>
        <?php endif; ?>
    </div>

    <div class="actions">
        <!-- Bouton pour rejouer (retour à la sélection de la difficulté) -->
        <a href="/game" class="button-action new-game">Nouvelle Partie</a>
        
        <!-- Bouton pour voir le classement (si vous avez un route /scores) -->
        <!-- <a href="/scores" class="button-action leaderboard">Classement</a> -->
    </div>
</div>

<style>
/* Styles spécifiques pour la page de fin de jeu */
.game-over-container {
    max-width: 600px;
    margin: 60px auto;
    padding: 30px;
    background: linear-gradient(135deg, #f0f9ff 0%, #cbe8ff 100%);
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    text-align: center;
    font-family: Arial, sans-serif;
}

h1 {
    color: #004d99;
    font-size: 2.2em;
    margin-bottom: 25px;
    text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.5);
}

.message-box {
    padding: 20px;
    border: 3px solid #007bff;
    border-radius: 10px;
    background-color: #e6f7ff;
    margin-bottom: 30px;
}

.congratulations {
    font-size: 1.4em;
    color: #28a745;
    font-weight: bold;
    margin-bottom: 15px;
}

.stats {
    margin: 20px 0;
    padding: 15px;
    background-color: #ffffff;
    border-radius: 8px;
    border: 1px solid #ddd;
    display: inline-block;
}

.stats p {
    margin: 5px 0;
    font-size: 1.1em;
}

.score {
    color: #dc3545; /* Rouge pour le score */
    font-size: 1.3em;
    font-weight: bold;
}

.score-info {
    margin-top: 20px;
    color: #555;
    font-style: italic;
}

.actions {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.button-action {
    display: inline-block;
    padding: 12px 25px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: bold;
    font-size: 1.1em;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.new-game {
    background-color: #ffc107; /* Jaune */
    color: #333;
    border: none;
}

.new-game:hover {
    background-color: #e0a800;
    transform: translateY(-2px);
}

.leaderboard {
    background-color: #6f42c1; /* Violet */
    color: white;
    border: none;
}

.leaderboard:hover {
    background-color: #5b37a1;
    transform: translateY(-2px);
}
</style>