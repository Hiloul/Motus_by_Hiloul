<?php
// Liste de mots à deviner
$wordsToGuess = ['chat', 'chien', 'chinchilla', 'serpent'];

// Choix d'un mot au hasard
$wordToGuess = $wordsToGuess[array_rand($wordsToGuess)];

// Connexion BDD motus
$host = 'localhost';
$db   = 'motus';
$user = 'root';
$pass = 'root';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

// Insérer le mot dans la base de données
$sql = "INSERT INTO words (word) VALUES (?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$wordToGuess]);

// Le mot proposé par l'utilisateur
$proposedWord = $_POST['word'];

// Préparation de la réponse
$response = [];

// Vérification des lettres
for ($i = 0; $i < strlen($proposedWord); $i++) {
    if ($proposedWord[$i] == $wordToGuess[$i]) {
        // Lettre bien placée
        $response[] = ['letter' => $proposedWord[$i], 'color' => 'green'];
    } elseif (strpos($wordToGuess, $proposedWord[$i]) !== false) {
        // Lettre mal placée
        $response[] = ['letter' => $proposedWord[$i], 'color' => 'orange'];
    } else {
        // Lettre inexistante
        $response[] = ['letter' => $proposedWord[$i], 'color' => 'red'];
    }
}

// Envoi de la réponse en JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
