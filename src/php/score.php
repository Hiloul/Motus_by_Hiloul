<?php
// Démarrer la session
session_start();

// JSON
header('Content-Type: application/json');

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

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $response = ['error' => 'Verifiez que vous êtes bien connecté(e).'];
    echo json_encode($response);
    exit();
}

$userId = $_SESSION['user_id'];

// Si un nom d'utilisateur est envoyé par la méthode POST, le stocker dans la session
if (isset($_POST['username'])) {
    $_SESSION['username'] = $_POST['username'];
}

// Récupérer tous les scores d'un utilisateur
function getUserScores($pdo, $userId) {
    $sql = "SELECT score FROM scores WHERE user_id = ? ORDER BY score DESC LIMIT 10"; // top 10
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

try {
    $scores = getUserScores($pdo, $userId);
    $response = ['scores' => $scores];
} catch (PDOException $e) {
    $response = ['error' => 'Une erreur est survenue lors de la récupération des scores.'];
} finally {
    // Fermer la connexion à la base de données
    $pdo = null;
}

// Envoi de la réponse en JSON
echo json_encode($response);
?>
