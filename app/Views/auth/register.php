<?php 
$error = $error ?? null; 
?>

<h1>Choix de votre Nom de Joueur</h1>

<?php if ($error): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form action="/register" method="POST">
    <div>
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required 
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
    </div>
    
    <button type="submit">Commencer à Jouer</button>
</form>