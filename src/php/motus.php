<?php
include "game.html";
// include "src/js/motusGame.js";
$actual_word = 'motus'; 

$guess = $_POST['guess'];

$result = [];

for ($i = 0; $i < strlen($actual_word); $i++) {
    if ($actual_word[$i] == $guess[$i]) {
        $result[$i] = 'red';  // Lettre correcte, bonne position
    } elseif (strpos($actual_word, $guess[$i]) !== false) {
        $result[$i] = 'yellow';  // Lettre correcte, mauvaise position
    } else {
        $result[$i] = 'none';  // Lettre incorrecte
    }
}

echo json_encode($result);
?>
