<?php 

$canClick = $canClick ?? true; 
$isGameOver = $isGameOver ?? false;

// Définir la route pour commencer un nouveau jeu

$newGameUrl = '/game/new'; 

?>


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


    <?php 
    // Bouton "Nouveau Jeu"
    // On l'affiche toujours (sauf peut-être pendant l'état de vérification si vous voulez le bloquer)
    ?>
    <div class="action-block-new">
        <form method="POST" action="<?= $newGameUrl ?>" style="display: inline;">
            <button type="submit" class="button new-game-button">Nouveau Jeu</button>
        </form>
    </div>
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
