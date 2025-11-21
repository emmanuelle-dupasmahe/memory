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

<header class="main-header">
    <div class="site-title">Memory Game - Snoopy Edition</div>
    
    <nav class="main-nav">
        <ul class="nav-list">
            <li><a href="/" class="nav-link">🏠</a></li> 
            <li><a href="/game" class="nav-link">Jouer au Memory 🃏</a></li>
            <li><a href="/leaderboard" class="nav-link">Classement 🏆</a></li>
            <li><a href="/register" class="nav-link">Inscription </a></li>
        </ul>
    </nav>
</header>
  
        
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