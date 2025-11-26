<?php 
/**
 * Vue : game/options.php
 * Permet de choisir le nombre de paires avant de lancer la partie.
 */
?>

<div class="options-container">
    <h1>Choisissez la difficulté</h1>
    <p>Sélectionnez le nombre de paires (entre 3 et 12) pour votre partie de Memory.</p>

    <form action="/game/start" method="POST" class="pairs-form">
        <label for="pairs" class="label-pairs">Nombre de Paires :</label>
        <input 
            type="number" 
            id="pairs" 
            name="pairs" 
            min="3" 
            max="12" 
            value="6" 
            required 
            class="input-pairs"
        >
        <button type="submit" class="button-start">Démarrer la Partie</button>
    </form>
    
    <p class="info-text">
        (3 paires = 6 cartes - Facile | 12 paires = 24 cartes - Difficile)
    </p>
</div>
