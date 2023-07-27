<?php
header('Content-Type: application/json');

$username = $_POST['username'];

// Votre code de connexion à la base de données ici...

$sql = "SELECT COUNT(*) FROM users WHERE username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$username]);
$count = $stmt->fetchColumn();

if ($count == 0) {
    echo json_encode(['error' => 'Ce nom d\'utilisateur n\'existe pas.']);
    exit();
}

// Si l'utilisateur existe, vous pouvez le considérer comme connecté.
// Dans une vraie application, vous voudriez probablement créer une session à ce stade.
echo json_encode(['success' => 'Vous êtes maintenant connecté.']);
?>
