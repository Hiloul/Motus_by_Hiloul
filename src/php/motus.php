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

$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['username']]);
$fetched_user_id_from_database = $stmt->fetchColumn();

$_SESSION['user_id'] = $fetched_user_id_from_database;

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Aucun utilisateur connecté."]);
    exit();
}

$userId = $_SESSION['user_id'];

// Verifier l'existence de l'utilisateur
$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    // User introuvable dans la database
    echo json_encode(['error' => 'Utilisateur non trouvé']);
    exit();
}

// Stocker le nom d'utilisateur dans la session
$_SESSION['username'] = $result['username'];

$_SESSION['nb_tentatives'] = 0;

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
    echo json_encode(['error' => 'Aucun mot entré']);
    exit();
}
$proposedWord = $_POST['word'];
// Préparation de la réponse
$response = [];
$isGuessed = true;

// Vérification des lettres
for ($i = 0; $i < strlen($proposedWord); $i++) {
    if ($proposedWord[$i] == $wordToGuess[$i]) {
        // Lettre bien placée VERTE
        $response[] = ['letter' => $proposedWord[$i], 'color' => 'green'];
    } elseif (strpos($wordToGuess, $proposedWord[$i]) !== false) {
        // Lettre mal placée ORANGE 
        $response[] = ['letter' => $proposedWord[$i], 'color' => 'orange'];
        $isGuessed = false;
    } else {
        // Lettre inexistante ROUGE
        $response[] = ['letter' => $proposedWord[$i], 'color' => 'red'];
        $isGuessed = false;
    }
}
// Le score est basé sur le nombre de tentatives
// 1 tentative = 100 points...
$attempts = $_SESSION['attempts'];
$score = $attempts ? (1 / $attempts) * 100 : 100;
// Insérer le mot dans la base de données s'il a été deviné
if ($isGuessed) {
    try {
        // Insérer le score et le mot deviné dans la table games
        $sql = "INSERT INTO games (user_id, word, nb_tentatives, score) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION['user_id'], $wordToGuess, $attempts, $score]);

        // Réinitialiser le mot à deviner pour la prochaine requête
        unset($_SESSION['wordToGuess']);
        unset($_SESSION['attempts']);  // reset plus tard
    } catch (PDOException $e) {
        // Afficher le message d'erreur SQL
        header('Content-Type: application/json');
        echo json_encode(["error" => $e->getMessage()]);
    } catch (Exception $e) {
        // Afficher le message d'erreur
        header('Content-Type: application/json');
        echo json_encode(["error" => $e->getMessage()]);
    }
}
// Incrémenter le compteur de tentatives si le mot n'a pas été deviné
if (!$isGuessed) {
    $_SESSION['attempts']++;
}
// Vérifier si le nombre de tentatives a dépassé la limite après incrémentation
if ($_SESSION['attempts'] >= 6) {
    $response = ['error' => 'Perdu ! Nombre de tentatives atteintes.', 'attempts' => $_SESSION['attempts']];
    header('Content-Type: application/json');
}