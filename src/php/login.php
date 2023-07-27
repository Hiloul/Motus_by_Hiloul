<?php
// Démarrer la session
session_start();

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

// Vérification que le nom d'utilisateur existe
$sql = "SELECT COUNT(*) FROM users WHERE username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$username]);
$count = $stmt->fetchColumn();

// Réponse JSON en cas d'utilisateur inexistant
if ($count == 0) {
    echo json_encode(['error' => 'Ce nom d\'utilisateur n\'existe pas.']);
    exit();
}

// Stocker le nom d'utilisateur dans la session
$_SESSION['username'] = $username;

echo json_encode(['success' => 'Connexion reussie !']);
?>
