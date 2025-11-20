<h2>👤 Profil de <?= $profile['username'] ?></h2>

<h3>Meilleurs Scores</h3>
<ul>
    <li>Meilleur Score Personnel : <strong><?= $profile['best_score'] ?> coups</strong></li>
    <li>Total des Parties Jouées : <strong><?= $profile['games_played'] ?></strong></li>
</ul>

<h3>Historique des Parties</h3>
<?php if (!empty($profile['scores_history'])): ?>
    <ul>
        <?php foreach ($profile['scores_history'] as $score): ?>
            <li><?= $score['coups'] ?> coups (le <?= date('d/m/Y H:i', strtotime($score['date_partie'])) ?>)</li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucune partie enregistrée pour l'instant.</p>
<?php endif; ?>