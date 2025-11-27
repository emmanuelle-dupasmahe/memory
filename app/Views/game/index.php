<?php 

$canClick = $canClick ?? true; 
$isGameOver = $isGameOver ?? false;

$message = $message ?? null;

// Définir la route pour commencer un nouveau jeu

$newGameUrl = '/game'; 

?>

<?php
// Vérifiez si l'utilisateur est connecté
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['username']);
$username = $_SESSION['username'] ?? 'Invité'; 
?>

<?php 
    // Déterminer la classe : 'success-message' ou 'error-message'
    $messageClass = (!empty($message) && strpos($message, 'Bravo') !== false) ? 'success-message' : 'error-message';
    
    // Si le message est vide, nous ajoutons une classe 'empty-message'
    $visibilityClass = empty($message) ? 'empty-message' : '';
?>

<div class="game-message <?= $messageClass ?> <?= $visibilityClass ?>">
<h2><?= !empty($message) ? htmlspecialchars($message) : '&nbsp;' ?></h2>
 
    <?php if ($isGameOver && !empty($message)): ?>
<p>🎉 Bravo ! Votre résultat est prêt.</p>
<?php endif; ?>
</div>

<div class="top-bar-game"> 

<div class="user-info">
    <?php if ($isLoggedIn): ?>
        <p>Bienvenue, <?= htmlspecialchars($username) ?> !</p>
    <?php else: ?>
        <p>Vous jouez en tant qu'Invité. <a href="/register">Inscrivez-vous ici</a> pour enregistrer votre score !</p>
    <?php endif; ?>
</div>

 <?php 
    // Bouton "Nouveau Jeu"
    ?>
    <div class="centered-action">
        <a href="<?= $newGameUrl ?>" class="button new-game-button link-button">Nouveau Jeu</a>
    </div>

</div>
<div class="action-bar">
    <?php 
    // Bouton "Continuer" 
    // Si DEUX cartes sont retournées, on affiche un bouton "Continuer"
    if (count($_SESSION['memory_flipped'] ?? []) === 2 && !$isGameOver): 
    ?>
        <div class="fixed-continue-prompt"> 
        <div class="action-block-continue">
            <p>Vérifiez la paire. Cliquez pour continuer...</p>
            <form method="POST" action="/game/checkAndReset" style="display: inline;">
                <button type="submit" class="button continue-button">Continuer</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    </div>

<div id="memory-grid" class="memory-grid">
    <?php foreach ($board as $cardData): ?>
        <?php 
            $classes = ['card'];
            if ($cardData['isFlipped']) { $classes[] = 'flipped'; }
            if ($cardData['isMatched']) { $classes[] = 'matched'; }
            // Désactiver le clic si deux cartes sont déjà retournées, ou si la carte est trouvée/déjà retournée
            $disabled = !$canClick || $cardData['isFlipped'] ? 'disabled' : ''; 
        ?>
        
        <form method="POST" action="/game/flip" style="display:inline;"> 
            <input type="hidden" name="board_id" value="<?= $cardData['boardId'] ?>">
            
            <button type="submit" class="card-button" <?= $disabled ?>>
                <div class="<?= implode(' ', $classes) ?>">
                    <div class="card-inner">
                        <div class="card-front">
                            <img src="<?= $cardData['imagePath'] ?>" alt="<?= $cardData['name'] ?>">
                        </div>
                        <div class="card-back">
                            <img src="/assets/images/verso.png" alt="Dos de la carte Peanuts">
                        </div>
                    </div>
                </div>
            </button>
        </form>
    <?php endforeach; ?>
</div>
