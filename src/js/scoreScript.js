// Lorsque le formulaire est soumis...
document.getElementById("wordForm").addEventListener("submit", function (e) {
    e.preventDefault();

    // Récupérer le mot proposé
    var proposedWord = document.getElementById("wordInput").value;

    // Créer une requête HTTP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "src/php/motus.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Envoyer la requête
    xhr.send("word=" + encodeURIComponent(proposedWord));

    // Lorsque la requête se termine...
    xhr.onload = function () {
        if (this.status == 200) {
            // Parse the response
            var response = JSON.parse(this.responseText);

            // Vérifier si le mot a été deviné
            if (response.isGuessed) {
                // Afficher un message de réussite
                alert("Bravo ! Vous avez deviné le mot.");
            } else {
                // Afficher un message d'échec
                alert("Dommage ! Réessayez.");
            }
        } else {
            // Gérer les erreurs de la requête
            alert("Une erreur s'est produite lors de la soumission du mot.");
        }
    };
});
