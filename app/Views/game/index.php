<?php 

$canClick = $canClick ?? true; 
$isGameOver = $isGameOver ?? false;

?>
<h1>Memory Game - Snoopy Edition</h1>

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

<?php 
// Si DEUX cartes sont retournées, on affiche un bouton "Continuer" pour simuler le délai
if (count($_SESSION['memory_flipped'] ?? []) === 2 && !$isGameOver): 
?>
    <div class="action-block">
        <p>Vérifiez la paire. Cliquez pour continuer...</p>
        <form method="POST" action="/game/checkAndReset">
            <button type="submit" class="button">Continuer</button>
        </form>
    </div>
<?php endif; ?>