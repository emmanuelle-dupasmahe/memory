<?php

/** * @var array $profile
 * * Le tableau $profile contient :
 * 'username', 'best_score', 'games_played', 'scores_history'
 */

$username = $profile['username'] ?? 'Utilisateur';
$bestScore = $profile['best_score'] ?? 'N/A';
$gamesPlayed = $profile['games_played'] ?? 0;
$history = $profile['scores_history'] ?? [];
?>


<div class="content-box profile-page">
<h1>Profil de <?= htmlspecialchars($username) ?></h1>

<?php if ($gamesPlayed > 0): ?>

    <div class="profile-stats">
        <h2>Statistiques Globales</h2>
        <p>Parties Jouées : <strong><?= $gamesPlayed ?></strong></p>
        <p>Meilleur Score : <strong><?= $bestScore ?></strong> coups (le plus petit est le meilleur)</p>
    </div>

    <hr>

    <div class="profile-history">
        <h2>Historique des 10 dernières Parties</h2>
        
        <?php if (!empty($history)): ?>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Coups effectués</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Nous limitons à 10 entrées ici pour la lisibilité de l'historique
                    $limitedHistory = array_slice($history, 0, 10);
                    
                    foreach ($limitedHistory as $game): 
                        // Utilisation du nom de colonne 'date_partie' de votre BDD
                        $date = new \DateTime($game['date_partie']); 
                    ?>
                        <tr>
                            <td><?= $date->format('d/m/Y H:i') ?></td>
                            <td><?= $game['coups'] ?></td> </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Cet utilisateur n'a pas encore terminé de parties enregistrées.</p>
        <?php endif; ?>
        
    </div>

<?php else: ?>
    <p>Bienvenue, <?= htmlspecialchars($username) ?> ! Vous n'avez pas encore terminé de partie. Commencez une partie pour enregistrer votre score et débloquer vos statistiques !</p>
<?php endif; ?>
</div>