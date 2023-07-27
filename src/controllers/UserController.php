<?php

class UserController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register() {
        $username = $_POST['username'];

        if (strlen($username) < 8) {
            return json_encode(['error' => 'Le nom d\'utilisateur doit contenir au moins 8 caractères.']);
        }

        $sql = "SELECT COUNT(*) FROM users WHERE username = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            return json_encode(['error' => 'Ce nom d\'utilisateur existe déjà.']);
        }

        $sql = "INSERT INTO users (username) VALUES (?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);

        return json_encode(['success' => 'Utilisateur enregistré avec succès.']);
    }
}
