<?php
session_start();
require 'Motus.php';

$pdo = new PDO('mysql:host=localhost;dbname=motus', 'root', 'root');

$motus = new Motus($pdo);

if (isset($_POST['motSaisi'])) {
    echo $motus->deviner($_POST['motSaisi']);
}

// Connexion à la BDD SQL "motus"
// $host = 'localhost';
// $db   = 'motus';
// $user = 'root';
// $pass = 'root';
// $charset = 'utf8mb3';

// $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
// $opt = [
//     PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//     PDO::ATTR_EMULATE_PREPARES   => false,
// ];
// $pdo = new PDO($dsn, $user, $pass, $opt);

//Table WORDS à rajouter
$sql = "CREATE TABLE words (
    id INT AUTO_INCREMENT PRIMARY KEY,
    word VARCHAR(255) NOT NULL
)";

$pdo->exec($sql);

// BDD + Table ok
require 'Score.php';

class Motus {
    private $pdo;
    private $motMystere;
    private $score;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->score = new Score();

        $stmt = $this->pdo->query("SELECT word FROM words ORDER BY RAND() LIMIT 1");
        $result = $stmt->fetch();

        $this->motMystere = $result['word'];
    }

    public function deviner($mot) {
        if ($mot === $this->motMystere) {
            $this->score->incrementScore(strlen($this->motMystere));
            return "Félicitations, vous avez trouvé le mot! Votre score est maintenant " . $this->score->getScore();
        }

        $reponse = "";
        for ($i = 0; $i < strlen($this->motMystere); $i++) {
            if ($i < strlen($mot) && $mot[$i] === $this->motMystere[$i]) {
                $reponse .= $mot[$i];
            } else {
                $reponse .= "_";
            }
        }

        return $reponse;
    }
}
?>
