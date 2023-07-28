<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['userId'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Verifiez que vous êtes bien connecté(e).']);
    exit();
}

$userId = $_SESSION['userId'];

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

// Récupérer tous les scores d'un utilisateur
function getUserScores($pdo, $userId) {
    $sql = "SELECT score, game_date FROM scores WHERE user_id = ? ORDER BY score DESC LIMIT 10"; // Get the top 10 scores
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

try {
    $scores = getUserScores($pdo, $userId);
    header('Content-Type: application/json');
    echo json_encode($scores);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Une erreur est survenue lors de la récupération des scores.']);
} finally {
    // Fermer la connexion à la base de données
    $pdo = null;
}
?>
