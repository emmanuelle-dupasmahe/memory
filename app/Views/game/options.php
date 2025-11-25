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

<!-- Optionnel: Styles CSS pour cette vue (à intégrer dans votre style.css ou balise <style>) -->
<style>
.options-container {
    max-width: 500px;
    margin: 50px auto;
    padding: 30px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    text-align: center;
}
.pairs-form {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    margin-top: 20px;
}
.label-pairs {
    font-size: 1.1em;
    font-weight: bold;
    color: #333;
}
.input-pairs {
    padding: 10px;
    border: 2px solid #ccc;
    border-radius: 8px;
    width: 100px;
    text-align: center;
    font-size: 1.2em;
}
.button-start {
    padding: 12px 25px;
    background-color: #4CAF50; /* Vert */
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1.1em;
    transition: background-color 0.3s ease;
}
.button-start:hover {
    background-color: #45a049;
}
.info-text {
    margin-top: 20px;
    font-size: 0.9em;
    color: #666;
}
</style>