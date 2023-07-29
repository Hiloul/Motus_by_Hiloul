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

// Calculer le score à partir du nombre de tentatives dans la table games
try {
    $sql = "INSERT INTO scores (user_id, score)
            SELECT user_id, COUNT(*) * 10 AS score
            FROM games
            GROUP BY user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}

// Afficher les scores calculés
$sql = "SELECT * FROM scores";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$scores = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($scores);
?>
