<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
</head>
<body>
    <h1>
      Bienvenue à toi,
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
        <a href="http://localhost/php/Motus_by_Hiloul/game.html">Commencer à jouer !</a>
        <a href="http://localhost/php/Motus_by_Hiloul/score.html">Mes scores</a>
        <a href="logout.php">Déconnecter</a>
    </div>
    <script src="src/js/scoreScript.js"></script>
</body>
</html>

