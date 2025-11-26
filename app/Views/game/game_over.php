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