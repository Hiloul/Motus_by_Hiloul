<?php
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

// Afficher les 10 meilleurs scores de tous les utilisateurs
function getBestScores($pdo) {
    $sql = "SELECT users.username, games.score FROM games JOIN users ON games.user_id = users.id ORDER BY games.score DESC LIMIT 10";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


try {
    $scores = getBestScores($pdo);
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
