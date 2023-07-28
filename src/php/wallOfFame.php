<?php
// JSON
header('Content-Type: application/json');

$username = $_POST['username'];
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

// Afficher les scores de l'utilisateur connecté
function getUserScores($pdo, $userId) {
    // On affiche les 10 meilleurs scores de la BDD
    $sql = "SELECT * FROM scores WHERE user_id = ? ORDER BY score DESC LIMIT 10";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$scores = getUserScores($pdo, $userId);

header('Content-Type: application/json');
echo json_encode($scores);