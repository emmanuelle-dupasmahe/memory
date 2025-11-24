<?php
/**
 * Layout principal
 * -----------------
 * Ce fichier définit la structure HTML commune à toutes les pages.
 * Il inclut dynamiquement le contenu spécifique à chaque vue via la variable $content.
 */
// Définition de l'état de connexion
$isLoggedIn = isset($_SESSION['user_id']);
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
            <li><a href="/game" class="nav-link">Jouer 🎮</a></li>
            <li><a href="/leaderboard" class="nav-link">Classement 🏆</a></li>

            <?php if ($isLoggedIn): ?>
                <li>
                    <a href="/profile" class="nav-link profile-link">
                        <?= htmlspecialchars($_SESSION['username']) ?> 👤
                    </a>
                </li>
                <li><a href="/logout" class="nav-link logout-link">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="/register" class="nav-link">Inscription</a></li>
            <?php endif; ?>
            
            </ul> 
    </nav> 
</header>

<main>
    <?= $content ?>
</main>
</body>
</html>