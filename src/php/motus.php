<?php
session_start();

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

// Liste de mots à deviner
$wordsToGuess = ['chat', 'chien', 'chinchilla', 'serpent'];

// Choix d'un mot au hasard si aucun mot n'a encore été choisi
if (!isset($_SESSION['wordToGuess'])) {
    $wordToGuess = $wordsToGuess[array_rand($wordsToGuess)];
    $_SESSION['wordToGuess'] = $wordToGuess;
    $_SESSION['attempts'] = 0;
} else {
    $wordToGuess = $_SESSION['wordToGuess'];
}

// Le mot proposé par l'utilisateur
if (!isset($_POST['word'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No word provided']);
    exit();
}

$proposedWord = $_POST['word'];

// Préparation de la réponse
$response = [];
$isGuessed = true;

// Vérification des lettres
for ($i = 0; $i < strlen($proposedWord); $i++) {
    if ($proposedWord[$i] == $wordToGuess[$i]) {
        // Lettre bien placée
        $response[] = ['letter' => $proposedWord[$i], 'color' => 'green'];
    } elseif (strpos($wordToGuess, $proposedWord[$i]) !== false) {
        // Lettre mal placée
        $response[] = ['letter' => $proposedWord[$i], 'color' => 'orange'];
        $isGuessed = false;
    } else {
        // Lettre inexistante
        $response[] = ['letter' => $proposedWord[$i], 'color' => 'red'];
        $isGuessed = false;
    }
}

// Insérer le mot dans la base de données s'il a été deviné
if ($isGuessed) {
    $sql = "INSERT INTO words (word) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$wordToGuess]);

    // Le score est basé sur le nombre de tentatives
    // Si $attempts vaut 0, évitez une division par zéro en attribuant un score de 0
    $score = $_SESSION['attempts'] ? (1 / $_SESSION['attempts']) * 100 : 0;

    $sql = "INSERT INTO scores (user_id, score) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id'], $score]);

    // Réinitialisez le mot à deviner pour la prochaine requête
    unset($_SESSION['wordToGuess']);
}

// Incrémenter le compteur de tentatives si le mot n'a pas été deviné
if (!$isGuessed) {
    $_SESSION['attempts']++;
}

// Vérifier si le nombre de tentatives a dépassé la limite après incrémentation
if ($_SESSION['attempts'] >= 6) {
    $response = ['error' => 'Perdu ! Nombre de tentatives atteintes.', 'attempts' => $_SESSION['attempts']];
    header('Content-Type: application/json');
    echo json_encode($response);
    // Réinitialisation du compteur apres les 6 tentatives échouées
    $_SESSION['attempts'] = 0;
    exit();
}

// Envoi de la réponse en JSON
$response = ['result' => $response, 'attempts' => $_SESSION['attempts']];
header('Content-Type: application/json');
echo json_encode($response);
?>
