<?php
require 'Motus.php';

// Connexion à la BDD "motus"
$pdo = new PDO('mysql:host=localhost;dbname=motus', 'root', 'root');

// $motus = new Motus($pdo);

if (isset($_POST['motSaisi'])) {
    echo $motus->deviner($_POST['motSaisi']);
}
?>


<!-- Table WORD à rajouter -->