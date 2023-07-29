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
$_SESSION['user_id'] = 1;

// Récupérer le nombre de tentatives de l'utilisateur
function getAttempts($pdo, $userId) {
    $sql = "SELECT COUNT(*) as attempts FROM games WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

try {
    $userId = $_SESSION['user_id'];
    $attempts = getAttempts($pdo, $userId);
    $response = ['attempts' => $attempts['attempts']];
} catch (PDOException $e) {
    $response = ['error' => 'Une erreur est survenue lors de la récupération des tentatives.'];
}

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
    $response['error'] = 'Aucun mot entré';
    echo json_encode($response);
    exit();
}

$proposedWord = $_POST['word'];


// Vérification des lettres
for ($i = 0; $i < strlen($proposedWord); $i++) {
    if ($proposedWord[$i] == $wordToGuess[$i]) {
        // Lettre bien placée VERTE
        $response['result'][] = ['letter' => $proposedWord[$i], 'color' => 'green'];
    } elseif (strpos($wordToGuess, $proposedWord[$i]) !== false) {
        // Lettre mal placée ORANGE 
        $response['result'][] = ['letter' => $proposedWord[$i], 'color' => 'orange'];
    } else {
        // Lettre inexistante ROUGE
        $response['result'][] = ['letter' => $proposedWord[$i], 'color' => 'red'];
    }
}

// Le score est basé sur le nombre de tentatives
// 1 tentative = 100 points...
$attempts = $_SESSION['attempts'];
$score = $attempts ? (1 / $attempts) * 100 : 100;

// Insérer le mot dans la base de données s'il a été deviné
if (isset($response['result']) && count($response['result']) == strlen($wordToGuess)) {
    try {
        // Insérer le score et le mot deviné dans la table games
        $sql = "INSERT INTO games (user_id, word, nb_tentatives, score) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION['user_id'], $wordToGuess, $attempts, $score]);

        // Réinitialiser le mot à deviner pour la prochaine requête
        unset($_SESSION['wordToGuess']);
        unset($_SESSION['attempts']);
    } catch (PDOException $e) {
        // Afficher le message d'erreur SQL
        $response['error'] = $e->getMessage();
        echo json_encode($response);
        exit();
    }
}

// Incrémenter le compteur de tentatives si le mot n'a pas été deviné
if (!isset($response['result']) || count($response['result']) != strlen($wordToGuess)) {
    $_SESSION['attempts']++;
}

// Vérifier si le nombre de tentatives a dépassé la limite après incrémentation
if ($_SESSION['attempts'] >= 6) {
    $response = ['error' => 'Perdu ! Nombre de tentatives atteintes.', 'attempts' => $_SESSION['attempts']];

    // Réinitialiser le mot à deviner et les tentatives
    $wordToGuess = $wordsToGuess[array_rand($wordsToGuess)];
    $_SESSION['wordToGuess'] = $wordToGuess;
    $_SESSION['attempts'] = 0;
}

header('Content-Type: application/json');
echo json_encode($response);
?>
