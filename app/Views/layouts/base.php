<?php
/**
 * Layout principal
 * -----------------
 * Ce fichier définit la structure HTML commune à toutes les pages.
 * Il inclut dynamiquement le contenu spécifique à chaque vue via la variable $content.
 */
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">

  <title><?= isset($title) ? htmlspecialchars($title, ENT_QUOTES, 'UTF-8') : 'Memory Snoopy' ?></title>

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/assets/global.css">
  
  <link rel="stylesheet" href="/assets/css/style.css"> 
</head>
<body>
  <nav>
    <ul>
        <li><a href="/">Accueil</a></li>
        <li><a href="/game">Jouer au Memory 🃏</a></li>
        <li><a href="/leaderboard">Classement 🏆</a></li>
        
        <?php 
        // OPTIONNEL : Lien vers le profil si l'utilisateur est "connecté" (simulé ici par la session)
        if (isset($_SESSION['username'])): 
        ?>
            <li>
                <a href="/profile?username=<?= urlencode($_SESSION['username'] ?? 'Visiteur') ?>">
                    Mon Profil
                </a>
            </li>
        <?php endif; ?>
        
        </ul>
  </nav>

  <main>
    <?= $content ?>
  </main>
</body>
</html>