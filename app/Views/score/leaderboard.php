<?php 
/** @var array $topScores */ 
?>

<h1>🏆 Classement des 10 meilleurs joueurs 🏆</h1>

<?php if (empty($topScores)): ?>
    <p>Aucun score enregistré pour l'instant. Soyez le premier à jouer !</p>
<?php else: ?>
    
    <table class="leaderboard-table">
        <thead>
            <tr>
                <th>Classement</th>
                <th>Joueur</th>
                <th>Meilleur Score</th>
                <th>Parties Jouées</th>
            </tr>
        </thead>
        <tbody>
            <?php $rank = 1; ?>
            <?php foreach ($topScores as $score): ?>
                <tr class="<?= $rank === 1 ? 'rank-gold' : '' ?>">
                    <td><?= $rank++ ?></td>
                    <td><?= htmlspecialchars($score['username']) ?></td>
                    <td> <?= $score['best_score'] ?> </td>
                    <td><?= $score['games_played'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
<?php endif; ?>
</table>