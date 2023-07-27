<?php
session_start();

// Liste de mots à deviner
$wordsToGuess = ['chat', 'chien', 'chinchilla', 'serpent'];

// Choix d'un mot au hasard
$wordToGuess = $wordsToGuess[array_rand($wordsToGuess)];
$_SESSION['wordToGuess'] = $wordToGuess;
$_SESSION['attempts'] = 0;

// Send a success response
header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>
