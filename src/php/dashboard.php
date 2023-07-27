<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>
      Bienvenue,
      <?php 
      if (isset($_SESSION['username'])) {
          echo $_SESSION['username']; 
      } else {
          echo "Invité";
      }
      ?>!
    </h1>
    <div class="container">
        <h3>Mon tableau de bord: </h3>
        <a href="game.html">Commencer à jouer !</a>
        <a href="index.html">Mes meilleurs scores</a>
        <a href="logout.php">Déconnecter</a>
    </div>
</body>
</html>
