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
require 'score.php';

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





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOTUS</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="motus.php"></script>
</head>
<body>
    <h1>Mo-mo-motus</h1>
    <form id="userForm">
            <label for="username">Nom d'utilisateur:</label><br>
            <input type="text" id="username" name="username" required><br>
            <input type="submit" value="Jouer">
        </form>
    <form id="formUsername">
        <input type="text" id="username" placeholder="Nom d'utilisateur"/>
        <button type="submit">Commencer</button>
    </form>
    <form id="formMotus" style="display: none;">
        <input type="text" id="motSaisi" placeholder="Devinez le mot"/>
        <button type="submit">Valider</button>
    </form>

    <p id="reponse"></p>
    <script src="src/js/script.js"></script>
</body>    
</html>



<?php
$username = $_POST['username'];

$host = 'localhost';
$db   = 'motus';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb3';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$pdo = new PDO($dsn, $user, $pass, $opt);

$sql = "INSERT INTO users (username) VALUES (?)";
$stmt= $pdo->prepare($sql);
$stmt->execute([$username]);

echo 'Utilisateur enregistré avec succès.';
?>


<?php
$username = $_POST['username'];

$servername = "localhost";
$database = "nom_de_votre_base_de_donnees";
$username = "nom_d_utilisateur_de_base_de_donnees";
$password = "mot_de_passe_de_base_de_donnees";



