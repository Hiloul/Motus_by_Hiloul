document.addEventListener("DOMContentLoaded", function() {
    var scoreButton = document.getElementById("scoreButton");

    if(scoreButton) {
        scoreButton.addEventListener("click", function() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "src/php/score.php", true);

            xhr.onload = function() {
                if (this.status == 200) {
                    try {
                        var response = JSON.parse(this.responseText);
                    } catch(e) {
                        alert('Erreur de parsing JSON: ' + e);
                        return;
                    }

                    var scoreList = document.getElementById("scoreList");
                    scoreList.innerHTML = ''; // clear existing scores
                    if (response.error) {
                        var errorItem = document.createElement("li");
                        errorItem.textContent = response.error;
                        scoreList.appendChild(errorItem);
                    } else if(Array.isArray(response)){
                        for (var i = 0; i < response.length; i++) {
                            var scoreItem = document.createElement("li");
                            scoreItem.textContent = "Score: " + response[i].score;
                            scoreList.appendChild(scoreItem);
                        }
                    } else {
                        alert('Réponse du serveur non reconnue.');
                    }
                } else {
                    alert('Une erreur est survenue lors de la requête au serveur. Code de statut : ' + this.status);
                }
            };

            xhr.onerror = function() {
                alert('Une erreur est survenue lors de la connexion au serveur.');
            };

            xhr.send();
        });
    }
});
