<h2>🏆 Classement des 10 Meilleurs Joueurs</h2>

<table>
    <thead>
        <tr>
            <th>Rang</th>
            <th>Joueur</th>
            <th>Meilleur Score (coups)</th>
            <th>Parties Jouées</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($players as $index => $player): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><a href="/score/profile/<?= $player['username'] ?>"><?= $player['username'] ?></a></td>
                <td><?= $player['best_score'] ?></td>
                <td><?= $player['games_played'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>