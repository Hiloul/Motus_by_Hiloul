<?php
session_start();
// $_SESSION['user_id'] = 1; // Utilisez l'ID de l'utilisateur que vous voulez tester


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

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Aucun utilisateur connecté.']);
    exit();
}

// Afficher les 10 meilleurs scores de l'utilisateur connecté
function getBestScores($pdo, $userId) {
    $sql = "SELECT score FROM games WHERE user_id = ? ORDER BY score DESC LIMIT 10";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

try {
    $userId = $_SESSION['user_id'];
    $scores = getBestScores($pdo, $userId);
    $response = ['topScores' => $scores];
} catch (PDOException $e) {
    $response = ['error' => 'Une erreur est survenue lors de la récupération des scores.'];
} finally {
    // Fermer la connexion à la base de données
    $pdo = null;
}

// Envoi de la réponse en JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
